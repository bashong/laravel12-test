<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '@/Common/api'
import { usePage } from '@inertiajs/vue3'

import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage()
const songs = ref(page.props.songs)
const queue = ref(page.props.queue)

const isShuffle = ref(false);
const currentSong = ref(null)
const isPlaying = ref(false)


// Play / Pause toggle
function togglePlay() {
    if(!currentSong.value){
        playNext()
    }
    isPlaying.value = !isPlaying.value
}

function toggleShuffle() {
    if(isShuffle.value && isPlaying.value){
        shufflePlay()
    }
    isShuffle.value = !isShuffle.value;
}

const play = async (song) => {
    if (!currentSong || currentSong.id !== song.id) {
        const res = await api('get', `/api/v1/music-player/play/${song.id}`)
        currentSong.value = res.data
        isPlaying.value = true
    }
}

const playNext = async () => {
    let orderBy = isShuffle.value ? 'rand' : 'asc';
    const response = await api('get', `/api/v1/music-player/next/${orderBy}`)
    currentSong.value = response.data
    isPlaying.value = true
}

const playPrevious = async () => {
    const response = await api('get', '/api/v1/music-player/previous')
    currentSong.value = response.data
    isPlaying.value = true
}

const shufflePlay = async () => {
    const response = await api('get', '/api/v1/music-player/shuffle')
    currentSong.value = response.data.song;
    isPlaying.value = true
}

const fetchQueue = async () => {
    const response = await api('get', '/api/v1/music-player/queue')
    queue.value = response.data
}

onMounted(async () => {
    fetchQueue()
})
</script>

<template>
    <AppLayout title="Music Player">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Music Player
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-col md:flex-row min-h-screen bg-purple-900 text-white p-6 md:p-8 gap-8">
                        <!-- Player -->
                        <div class="flex flex-col items-center space-y-6 w-full md:w-1/3 order-2 md:order-none">

                            <img v-if="currentSong" :src="currentSong.image" alt="Album"
                                class="object-cover w-50 h-50" />

                            <svg v-else class="object-cover w-50 h-50" viewBox="-1.76 -1.76 19.52 19.52" fill="none"
                                xmlns="http://www.w3.org/2000/svg" stroke="#8f3196" stroke-width="0.096"
                                transform="rotate(0)matrix(1, 0, 0, 1, 0, 0)">
                                <g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(0,0), scale(1)">
                                    <path transform="translate(-1.76, -1.76), scale(0.61)"
                                        d="M16,24.1861119735986C19.59057415480688,24.39930688088399,23.60667626194481,26.611624901014935,26.256384189389173,24.179193457192092C29.09992303730038,21.568825260097206,29.00707698116028,16.979337526907173,27.868121013191868,13.291178825466343C26.82689598653017,9.919491727597087,23.712304618398058,8.07387187233182,20.710122412263594,6.219330030459744C16.99868177150099,3.926657071485287,13.211849681971671,0.20254222821576984,9.117494730232117,1.7083305453051292C4.832364016726835,3.284280808937155,2.9123267980499605,8.371122147099971,2.496286254664515,12.917865457594601C2.1399053285010545,16.812612298458102,4.162199639610469,20.539861831017905,7.165069034421222,23.045622337662195C9.563748657125062,25.04721344150789,12.881387749426466,24.00094040466442,16,24.1861119735986"
                                        fill="#cf7eec" strokewidth="0"></path>
                                </g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"
                                    stroke="#CCCCCC" stroke-width="0.064"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M15 1H4V9H3C1.34315 9 0 10.3431 0 12C0 13.6569 1.34315 15 3 15C4.65685 15 6 13.6569 6 12V5H13V9H12C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12V1Z"
                                        fill="#cc7575"></path>
                                </g>
                            </svg>

                            <div class="text-center" v-if="currentSong">
                                <h2 class="text-xl font-bold">{{ currentSong.title }}</h2>
                                <p class="text-sm text-purple-300">{{ currentSong.artist }}</p>
                            </div>

                            <div class="flex items-center space-x-6">
                                {{ isShuffle }}
                                {{ isShuffle ? 'rand' : 'asc' }}
                                <button @click="toggleShuffle"
                                :class="isShuffle ? 'bg-purple-600 text-white' : 'bg-white text-purple-600'"
                                ><i class="fas fa-random"></i></button>
                                <button @click="playPrevious">
                                    <i class="fas fa-step-backward"></i>
                                </button>
                                <!-- Play / Pause Button -->
                                <button
                                    class="w-14 h-14 rounded-full bg-white text-purple-900 flex items-center justify-center shadow-lg"
                                    @click="togglePlay">
                                    <i :class="isPlaying ? 'fas fa-pause' : 'fas fa-play'"></i>
                                </button>
                                <button @click="playNext"><i class="fas fa-step-forward"></i></button>
                            </div>

                            <div v-if="currentSong" class="flex items-center space-x-4 w-full">
                                <span class="text-xs">4:00</span>
                                <div class="flex-1 bg-purple-700 h-1 rounded-full">
                                    <div class="bg-white h-1 rounded-full w-1/3"></div>
                                </div>
                                <span class="text-xs">03:16</span>
                            </div>
                        </div>
                        <!-- Playlist -->
                        <div class="flex-1 space-y-6 order-1 md:order-none">
                            <div v-for="(song, index) in songs" :key="index"
                                class="flex justify-between items-center border-b border-purple-700 py-2"
                                :class="currentSong && currentSong.id === song.id ? 'bg-purple-800' : ''">
                                <!-- LEFT: title + artist -->
                                <div>
                                    <h3 @click="play(song)" :class="[
                                        'font-semibold flex items-center space-x-2',
                                        currentSong && currentSong.id === song.id
                                            ? 'text-purple-300 cursor-default'
                                            : 'hover:text-purple-400 cursor-pointer'
                                    ]">
                                        {{ song.title }}
                                    </h3>
                                    <p class="text-sm text-purple-300">{{ song.artist }}</p>
                                </div>

                                <!-- RIGHT: duration + icon -->
                                <div class="flex items-center space-x-2">
                                    <span>{{ song.duration || "04:00"}}</span>
                                    <i
                                    v-if="currentSong && currentSong.id === song.id"
                                    :class="[
                                    'fas',
                                    isPlaying ? 'fa-music' : 'fa-pause',
                                    'text-white animate-pulse'
                                    ]"
                                    ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
i {
    font-size: 1.5rem;
}
</style>
