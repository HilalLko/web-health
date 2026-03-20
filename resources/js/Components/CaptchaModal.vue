<template>
    <!-- Backdrop -->
    <Teleport to="body">
        <Transition name="backdrop">
            <div
                v-if="visible"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="background: rgba(0,0,0,0.55); backdrop-filter: blur(6px);"
            >
                <!-- Modal Card -->
                <Transition name="modal">
                    <div
                        v-if="visible"
                        class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-white/20 dark:border-slate-700 overflow-hidden"
                        @click.stop
                    >
                        <!-- Header gradient bar -->
                        <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                        <!-- Close button -->
                        <button
                            @click="cancel"
                            class="absolute top-4 right-4 p-1.5 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all"
                            aria-label="Close"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <div class="p-8">
                            <!-- Shield Icon -->
                            <div class="flex justify-center mb-5">
                                <div class="relative">
                                    <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <!-- Animated ring -->
                                    <div class="absolute inset-0 rounded-full border-2 border-indigo-400/50 animate-ping"></div>
                                </div>
                            </div>

                            <h2 class="text-center text-xl font-bold text-gray-900 dark:text-white mb-1">Human Verification</h2>
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-7">
                                Prove you're human to start the analysis
                            </p>

                            <!-- ===== CHALLENGE: Slider ===== -->
                            <div v-if="!verified">
                                <!-- Step indicator -->
                                <div class="flex items-center justify-center gap-2 mb-6">
                                    <div class="h-1 w-12 rounded-full transition-colors duration-300"
                                        :class="step >= 1 ? 'bg-indigo-500' : 'bg-gray-200 dark:bg-slate-600'"></div>
                                    <div class="h-1 w-12 rounded-full transition-colors duration-300"
                                        :class="step >= 2 ? 'bg-purple-500' : 'bg-gray-200 dark:bg-slate-600'"></div>
                                    <div class="h-1 w-12 rounded-full transition-colors duration-300"
                                        :class="step >= 3 ? 'bg-pink-500' : 'bg-gray-200 dark:bg-slate-600'"></div>
                                </div>

                                <!-- STEP 1: Drag-slider -->
                                <div v-if="step === 1">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center mb-4">
                                        Slide to the end to verify
                                    </p>
                                    <div
                                        ref="sliderTrack"
                                        class="relative w-full h-14 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-slate-700 dark:to-slate-700 rounded-xl border-2 border-dashed border-indigo-200 dark:border-slate-500 overflow-hidden select-none"
                                        @mousemove="onSliderMove"
                                        @mouseup="onSliderEnd"
                                        @mouseleave="onSliderEnd"
                                        @touchmove.prevent="onSliderTouchMove"
                                        @touchend="onSliderEnd"
                                    >
                                        <!-- Fill -->
                                        <div
                                            class="absolute inset-y-0 left-0 rounded-xl transition-colors duration-200"
                                            :class="sliderFill"
                                            :style="{ width: sliderPct + '%' }"
                                        ></div>
                                        <!-- Hint text -->
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500"
                                                :style="{ opacity: sliderPct > 30 ? 0 : 1, transition: 'opacity 0.3s' }">
                                                ←  Drag the handle  →
                                            </span>
                                        </div>
                                        <!-- Handle -->
                                        <div
                                            class="absolute top-1 bottom-1 w-12 rounded-lg shadow-lg flex items-center justify-center cursor-grab active:cursor-grabbing transition-all duration-100"
                                            :class="handleColor"
                                            :style="{ left: handleLeft }"
                                            @mousedown="onSliderStart"
                                            @touchstart="onSliderTouchStart"
                                        >
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M13 5l7 7-7 7M6 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p v-if="sliderError" class="text-red-500 text-xs text-center mt-2 animate-shake">
                                        Almost! Please slide all the way to the end.
                                    </p>
                                </div>

                                <!-- STEP 2: Math challenge -->
                                <div v-if="step === 2">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center mb-4">
                                        Solve the quick math challenge
                                    </p>
                                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-slate-700 dark:to-slate-700 rounded-xl p-5 text-center mb-4 border border-indigo-100 dark:border-slate-600">
                                        <span class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-wide">
                                            {{ mathQ.a }} {{ mathQ.op }} {{ mathQ.b }} = ?
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3">
                                        <button
                                            v-for="opt in mathOptions"
                                            :key="opt"
                                            @click="checkMath(opt)"
                                            class="py-3 rounded-xl font-bold text-lg transition-all duration-150 border-2"
                                            :class="mathSelected === opt
                                                ? (opt === mathQ.answer ? 'bg-green-500 border-green-500 text-white scale-105' : 'bg-red-500 border-red-500 text-white animate-shake')
                                                : 'bg-white dark:bg-slate-800 border-indigo-200 dark:border-slate-600 text-gray-800 dark:text-white hover:border-indigo-400 hover:scale-105'"
                                        >{{ opt }}</button>
                                    </div>
                                    <p v-if="mathError" class="text-red-500 text-xs text-center mt-3 animate-shake">
                                        Incorrect answer — try again!
                                    </p>
                                </div>

                                <!-- STEP 3: Click the correct tile -->
                                <div v-if="step === 3">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center mb-4">
                                        Select the tile that shows: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ tileTarget }}</span>
                                    </p>
                                    <div class="grid grid-cols-3 gap-3">
                                        <button
                                            v-for="(tile, i) in tiles"
                                            :key="i"
                                            @click="checkTile(i)"
                                            class="h-16 rounded-xl flex items-center justify-center text-2xl font-bold transition-all duration-150 border-2"
                                            :class="tileSelected === i
                                                ? (i === tileCorrect ? 'bg-green-500 border-green-500 text-white scale-105' : 'bg-red-400 border-red-400 text-white animate-shake')
                                                : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-600 hover:border-indigo-400 hover:scale-105'"
                                        >{{ tile }}</button>
                                    </div>
                                    <p v-if="tileError" class="text-red-500 text-xs text-center mt-3 animate-shake">
                                        That's not the right tile — try again!
                                    </p>
                                </div>
                            </div>

                            <!-- ===== SUCCESS STATE ===== -->
                            <div v-else class="text-center py-4">
                                <div class="flex justify-center mb-4">
                                    <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center animate-scale-in">
                                        <svg class="w-9 h-9 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-lg font-bold text-gray-900 dark:text-white mb-1">Verified! 🎉</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Starting website analysis...</p>
                                <div class="w-full h-1.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-progress"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const emit = defineEmits(['verified', 'cancel']);

const props = defineProps({
    visible: { type: Boolean, default: false },
});

// ── State ──────────────────────────────────────────────────
const step     = ref(1);
const verified = ref(false);

// Slider
const sliderTrack  = ref(null);
const sliderPct    = ref(0);
const isDragging   = ref(false);
const sliderError  = ref(false);
const dragStartX   = ref(0);
const dragStartPct = ref(0);

// Math
const mathQ        = ref({});
const mathOptions  = ref([]);
const mathSelected = ref(null);
const mathError    = ref(false);

// Tiles
const tiles        = ref([]);
const tileTarget   = ref('');
const tileCorrect  = ref(0);
const tileSelected = ref(null);
const tileError    = ref(false);

// ── Computed ───────────────────────────────────────────────
const handleLeft = computed(() => {
    const trackW = sliderTrack.value?.offsetWidth ?? 320;
    const maxLeft = trackW - 52;          // 52 = handle width + padding
    return Math.round((sliderPct.value / 100) * maxLeft) + 'px';
});

const handleColor = computed(() => {
    if (sliderPct.value >= 95) return 'bg-green-500';
    if (sliderPct.value >= 50) return 'bg-purple-500';
    return 'bg-indigo-600';
});

const sliderFill = computed(() => {
    if (sliderPct.value >= 95) return 'bg-green-200 dark:bg-green-800/40';
    return 'bg-indigo-200/60 dark:bg-indigo-800/30';
});

// ── Lifecycle ──────────────────────────────────────────────
watch(() => props.visible, (val) => {
    if (val) resetAll();
});

// ── Reset ──────────────────────────────────────────────────
function resetAll() {
    step.value = 1;
    verified.value = false;
    resetSlider();
    generateMath();
    generateTiles();
}

// ── Slider logic ───────────────────────────────────────────
function resetSlider() {
    sliderPct.value = 0;
    isDragging.value = false;
    sliderError.value = false;
}

function pctFromEvent(clientX) {
    const rect = sliderTrack.value?.getBoundingClientRect();
    if (!rect) return 0;
    return Math.min(100, Math.max(0, ((clientX - rect.left) / rect.width) * 100));
}

function onSliderStart(e) {
    isDragging.value = true;
    sliderError.value = false;
    dragStartX.value = e.clientX;
    dragStartPct.value = sliderPct.value;
    e.preventDefault();
}
function onSliderMove(e) {
    if (!isDragging.value) return;
    sliderPct.value = pctFromEvent(e.clientX);
}
function onSliderEnd() {
    if (!isDragging.value) return;
    isDragging.value = false;
    if (sliderPct.value >= 95) {
        advanceStep();
    } else {
        sliderError.value = true;
        // Snap back after a moment
        setTimeout(() => {
            sliderPct.value = 0;
            sliderError.value = false;
        }, 900);
    }
}
function onSliderTouchStart(e) {
    isDragging.value = true;
    sliderError.value = false;
}
function onSliderTouchMove(e) {
    if (!isDragging.value) return;
    sliderPct.value = pctFromEvent(e.touches[0].clientX);
}

// ── Math logic ─────────────────────────────────────────────
function generateMath() {
    const ops   = ['+', '-', '×'];
    const op    = ops[Math.floor(Math.random() * ops.length)];
    let a, b, answer;
    if (op === '+')      { a = rand(1,20); b = rand(1,20); answer = a + b; }
    else if (op === '-') { a = rand(10,30); b = rand(1, a); answer = a - b; }
    else                 { a = rand(2,9);  b = rand(2,9);  answer = a * b; }

    // 6 options: correct + 5 distractors
    const opts = new Set([answer]);
    while (opts.size < 6) opts.add(answer + rand(-10, 10));
    mathQ.value = { a, b, op, answer };
    mathOptions.value = shuffle([...opts]);
    mathSelected.value = null;
    mathError.value = false;
}

function checkMath(opt) {
    mathSelected.value = opt;
    if (opt === mathQ.value.answer) {
        setTimeout(advanceStep, 600);
    } else {
        mathError.value = true;
        setTimeout(() => {
            mathSelected.value = null;
            mathError.value = false;
            generateMath();
        }, 1000);
    }
}

// ── Tile logic ─────────────────────────────────────────────
const TILE_ICONS = ['🟦','🔴','🟩','⭐','🔷','💜','🌙','🔶','💚','🔺'];
function generateTiles() {
    const correct = Math.floor(Math.random() * 9);
    const target  = TILE_ICONS[Math.floor(Math.random() * TILE_ICONS.length)];
    const t = [];
    for (let i = 0; i < 9; i++) {
        if (i === correct) { t.push(target); }
        else {
            let icon;
            do { icon = TILE_ICONS[Math.floor(Math.random() * TILE_ICONS.length)]; }
            while (icon === target);
            t.push(icon);
        }
    }
    tiles.value = t;
    tileTarget.value = target;
    tileCorrect.value = correct;
    tileSelected.value = null;
    tileError.value = false;
}

function checkTile(i) {
    tileSelected.value = i;
    if (i === tileCorrect.value) {
        setTimeout(advanceStep, 600);
    } else {
        tileError.value = true;
        setTimeout(() => {
            tileSelected.value = null;
            tileError.value = false;
            generateTiles();
        }, 1000);
    }
}

// ── Step advancement ───────────────────────────────────────
function advanceStep() {
    if (step.value < 3) {
        step.value++;
    } else {
        verified.value = true;
        setTimeout(() => emit('verified'), 1400);
    }
}

function cancel() {
    emit('cancel');
}

// ── Helpers ────────────────────────────────────────────────
function rand(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}
</script>

<style scoped>
.backdrop-enter-active, .backdrop-leave-active { transition: opacity 0.3s ease; }
.backdrop-enter-from, .backdrop-leave-to       { opacity: 0; }

.modal-enter-active  { transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
.modal-leave-active  { transition: all 0.2s ease-in; }
.modal-enter-from    { opacity: 0; transform: scale(0.85) translateY(20px); }
.modal-leave-to      { opacity: 0; transform: scale(0.9); }

@keyframes scale-in {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.animate-scale-in { animation: scale-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }

@keyframes progress-bar {
    0%   { width: 0%; }
    100% { width: 100%; }
}
.animate-progress { animation: progress-bar 1.3s ease-in-out forwards; }

@keyframes shake {
    0%,100% { transform: translateX(0); }
    25%      { transform: translateX(-6px); }
    75%      { transform: translateX(6px); }
}
.animate-shake { animation: shake 0.4s ease-in-out; }
</style>
