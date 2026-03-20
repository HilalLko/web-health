<template>
    <Head title="Website Health Monitor" />
    
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-indigo-950 py-10 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12 animate-fade-in">
                <div class="inline-block mb-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 blur-2xl opacity-20 animate-pulse"></div>
                        <h1 class="relative text-5xl md:text-6xl font-extrabold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent animate-gradient">
                            Website Health Monitor
                        </h1>
                    </div>
                </div>
                
                <!-- Interactive Feature Icons -->
                <div class="flex flex-wrap items-center justify-center gap-4 mb-6">
                    <!-- HTTP Status Icon -->
                    <div class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-xl border border-indigo-200 dark:border-indigo-800 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 cursor-pointer animate-float" style="animation-delay: 0ms">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-300">HTTP</span>
                        </div>
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                            HTTP Status Check
                        </div>
                    </div>

                    <!-- SSL Certificate Icon -->
                    <div class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-xl border border-green-200 dark:border-green-800 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 cursor-pointer animate-float" style="animation-delay: 100ms">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-green-700 dark:text-green-300">SSL</span>
                        </div>
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                            SSL Certificate
                        </div>
                    </div>

                    <!-- DNS Icon -->
                    <div class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-xl border border-blue-200 dark:border-blue-800 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 cursor-pointer animate-float" style="animation-delay: 200ms">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                            </svg>
                            <span class="text-xs font-semibold text-blue-700 dark:text-blue-300">DNS</span>
                        </div>
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                            DNS Resolution
                        </div>
                    </div>

                    <!-- Security Headers Icon -->
                    <div class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-xl border border-red-200 dark:border-red-800 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 cursor-pointer animate-float" style="animation-delay: 300ms">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-red-700 dark:text-red-300">Security</span>
                        </div>
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                            Security Headers
                        </div>
                    </div>

                    <!-- Performance Icon -->
                    <div class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-xl border border-purple-200 dark:border-purple-800 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 cursor-pointer animate-float" style="animation-delay: 400ms">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-purple-700 dark:text-purple-300">Performance</span>
                        </div>
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                            Lighthouse Scores
                        </div>
                    </div>

                    <!-- Broken Links Icon -->
                    <div class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-xl border border-yellow-200 dark:border-yellow-800 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 cursor-pointer animate-float" style="animation-delay: 500ms">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <span class="text-xs font-semibold text-yellow-700 dark:text-yellow-300">Links</span>
                        </div>
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                            Broken Links Scan
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Comprehensive real-time analysis of HTTP, SSL, DNS, Security Headers, and Performance metrics.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-2xl rounded-2xl p-8 mb-10 border border-white/20 dark:border-slate-700/50 transform hover:scale-[1.01] transition-all duration-300 animate-slide-up">
                <form @submit.prevent="handleAnalyzeClick" class="space-y-6">
                    <div>
                        <label for="url" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Website URL
                        </label>
                        <div class="relative group">
                            <input
                                v-model="form.url"
                                type="url"
                                name="url"
                                id="url"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200 placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="https://example.com"
                                required
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                            </div>
                        </div>
                        <div v-if="form.errors.url" class="text-red-500 text-sm mt-2 flex items-center animate-shake">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ form.errors.url }}
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-100 dark:border-indigo-800 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-colors">
                        <input
                            v-model="form.realTime"
                            id="realTime"
                            name="realTime"
                            type="checkbox"
                            class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer transition-transform hover:scale-110"
                        />
                        <label for="realTime" class="ml-3 cursor-pointer">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Enable Real-Time Monitoring</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Automatically re-check every 5 minutes</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-base font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 relative overflow-hidden group"
                    >
                        <span class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        <span class="relative flex items-center">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-if="form.processing">Starting Analysis...</span>
                            <span v-else class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Analyze Website
                                <svg class="w-4 h-4 ml-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </span>
                        </span>
                    </button>
                </form>

                <!-- CAPTCHA Modal -->
                <CaptchaModal
                    :visible="showCaptcha"
                    @verified="onCaptchaVerified"
                    @cancel="onCaptchaCancel"
                />
            </div>

            <!-- Fun Loading Animation -->
            <div v-if="analyzing && !results" class="text-center py-16 animate-fade-in">
                <div class="inline-flex flex-col items-center">
                    <!-- Animated Robot Scanner -->
                    <div class="relative w-64 h-64 mb-8">
                        <!-- Scanning Beam -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-48 h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent animate-scan"></div>
                        </div>
                        
                        <!-- Orbiting Dots -->
                        <div class="absolute inset-0 animate-spin-slow">
                            <div class="absolute top-0 left-1/2 w-4 h-4 bg-indigo-500 rounded-full -ml-2 animate-pulse"></div>
                            <div class="absolute bottom-0 left-1/2 w-4 h-4 bg-purple-500 rounded-full -ml-2 animate-pulse delay-100"></div>
                            <div class="absolute left-0 top-1/2 w-4 h-4 bg-pink-500 rounded-full -mt-2 animate-pulse delay-200"></div>
                            <div class="absolute right-0 top-1/2 w-4 h-4 bg-blue-500 rounded-full -mt-2 animate-pulse delay-300"></div>
                        </div>
                        
                        <!-- Center Pulse -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full animate-ping opacity-20"></div>
                            <div class="absolute w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-full animate-bounce-slow flex items-center justify-center">
                                <svg class="w-8 h-8 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loading Messages -->
                    <div class="space-y-2">
                        <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 animate-pulse">
                            {{ loadingMessage }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">This may take 30-60 seconds</p>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-64 h-2 bg-gray-200 dark:bg-slate-700 rounded-full mt-6 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-full animate-progress"></div>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div v-if="results" class="space-y-6 animate-fade-in">
                <!-- Results Header -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl rounded-2xl p-6 border border-white/20 dark:border-slate-700/50 animate-slide-up">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                Analysis Results
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 break-all">
                                {{ results.url }}
                            </p>
                        </div>
                        <a 
                            :href="`/download/${resultsHash}`" 
                            target="_blank" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-500/50 transform hover:scale-105 transition-all duration-200"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF Report
                        </a>
                    </div>
                </div>

                <!-- Main Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- HTTP Status -->
                    <MetricCard
                        title="HTTP Status"
                        :status="results.status >= 200 && results.status < 300 ? 'success' : 'error'"
                        icon="check-circle"
                        :delay="0"
                    >
                        <div class="text-4xl font-bold mb-2">{{ results.status }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Response: {{ results.responseTime }}ms</div>
                        <div v-if="results.error" class="text-xs text-red-600 dark:text-red-400 mt-2">{{ results.error }}</div>
                    </MetricCard>

                    <!-- SSL Certificate -->
                    <MetricCard
                        title="SSL Certificate"
                        :status="results.sslValid ? 'success' : (results.sslValid === false ? 'error' : 'neutral')"
                        icon="shield-check"
                        :delay="100"
                    >
                        <div v-if="results.sslValid">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">Valid ✓</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                <div><strong>Issuer:</strong> {{ results.issuer }}</div>
                                <div><strong>Expires:</strong> {{ results.expiration }}</div>
                            </div>
                        </div>
                        <div v-else-if="results.sslValid === false">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400 mb-2">Invalid ✗</div>
                            <div class="text-xs text-red-600 dark:text-red-400">{{ results.sslError }}</div>
                        </div>
                        <div v-else>
                            <div class="text-lg text-gray-500 dark:text-gray-400">Not Applicable</div>
                        </div>
                    </MetricCard>

                    <!-- DNS Resolution -->
                    <MetricCard
                        title="DNS Resolution"
                        :status="results.dnsError ? 'error' : (results.dnsSlow ? 'warning' : 'success')"
                        icon="server"
                        :delay="200"
                    >
                        <div class="text-3xl font-bold mb-2">{{ results.dnsTime }}ms</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ results.dnsRecords ? results.dnsRecords.length : 0 }} records found</div>
                        <div v-if="results.dnsSlow" class="text-xs text-yellow-600 dark:text-yellow-400 mt-2">⚠ Slow resolution (&gt;100ms)</div>
                        <div v-if="results.dnsError" class="text-xs text-red-600 dark:text-red-400 mt-2">{{ results.dnsError }}</div>
                    </MetricCard>
                </div>

                <!-- Lighthouse Scores -->
                <div v-if="results.performanceScore !== null" class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl rounded-2xl p-6 border border-white/20 dark:border-slate-700/50 animate-slide-up" style="animation-delay: 300ms">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600 animate-bounce-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Lighthouse Scores
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <ScoreCircle label="Performance" :score="results.performanceScore" :delay="0" />
                        <ScoreCircle label="Accessibility" :score="results.accessibilityScore" :delay="100" />
                        <ScoreCircle label="Best Practices" :score="results.bestPracticesScore" :delay="200" />
                        <ScoreCircle label="SEO" :score="results.seoScore" :delay="300" />
                    </div>
                </div>

                <!-- Core Web Vitals -->
                <div v-if="hasMetrics" class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl rounded-2xl p-6 border border-white/20 dark:border-slate-700/50 animate-slide-up" style="animation-delay: 400ms">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Core Web Vitals & Metrics
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <MetricBadge v-if="results.metrics.lcp" label="LCP" :value="results.metrics.lcp + ' ms'" description="Largest Contentful Paint" :delay="0" />
                        <MetricBadge v-if="results.metrics.fcp" label="FCP" :value="results.metrics.fcp + ' ms'" description="First Contentful Paint" :delay="50" />
                        <MetricBadge v-if="results.metrics.cls" label="CLS" :value="results.metrics.cls" description="Cumulative Layout Shift" :delay="100" />
                        <MetricBadge v-if="results.metrics.tti" label="TTI" :value="results.metrics.tti + ' ms'" description="Time to Interactive" :delay="150" />
                        <MetricBadge v-if="results.metrics.tbt" label="TBT" :value="results.metrics.tbt + ' ms'" description="Total Blocking Time" :delay="200" />
                    </div>
                </div>

                <!-- Security Headers -->
                <div v-if="results.securityHeaders" class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl rounded-2xl p-6 border border-white/20 dark:border-slate-700/50 animate-slide-up" style="animation-delay: 500ms">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-red-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Security Headers Analysis
                    </h4>
                    <div class="space-y-3">
                        <SecurityHeader 
                            v-for="(header, name, index) in results.securityHeaders" 
                            :key="name"
                            :name="name"
                            :info="header"
                            :delay="index * 50"
                        />
                    </div>
                </div>

                <!-- Broken Links -->
                <div v-if="results.brokenLinks && results.brokenLinks.length > 0" class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl rounded-2xl p-6 border border-white/20 dark:border-slate-700/50 animate-slide-up" style="animation-delay: 600ms">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-yellow-600 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        Broken Links Found ({{ results.brokenLinks.length }})
                    </h4>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        <div 
                            v-for="(link, idx) in results.brokenLinks" 
                            :key="idx"
                            class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 rounded-r text-sm animate-slide-in-right"
                            :style="`animation-delay: ${idx * 50}ms`"
                        >
                            <div class="font-mono text-xs text-gray-700 dark:text-gray-300 break-all">{{ link.url }}</div>
                            <div class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">
                                Status: {{ link.status }}{{ link.error ? ' - ' + link.error : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Checks -->
            <div v-if="recent && recent.length > 0" class="mt-12 animate-fade-in">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Recent Checks</h3>
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl rounded-2xl overflow-hidden border border-white/20 dark:border-slate-700/50">
                    <ul class="divide-y divide-gray-200 dark:divide-slate-700">
                        <li v-for="(check, idx) in recent" :key="check.hash" class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-150 animate-slide-in-right" :style="`animation-delay: ${idx * 50}ms`">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">{{ check.url }}</p>
                                <div class="ml-4 flex-shrink-0 flex items-center space-x-3">
                                    <span 
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                        :class="check.status >= 200 && check.status < 300 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'"
                                    >
                                        {{ check.status }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ check.timestamp }}</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-16 mb-8 animate-fade-in">
                <div class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl shadow-lg rounded-2xl p-8 border border-white/20 dark:border-slate-700/50">
                    <div class="text-center space-y-4">
                        <!-- Copyright -->
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            © {{ new Date().getFullYear() }} Website Health Monitor. All rights reserved.
                        </p>
                        
                        <!-- Made with love -->
                        <div class="flex items-center justify-center space-x-2 text-sm text-gray-700 dark:text-gray-300">
                            <span>Built with</span>
                            <svg class="w-5 h-5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            <span>using</span>
                        </div>

                        <!-- Tech Stack -->
                        <div class="flex flex-wrap items-center justify-center gap-4">
                            <!-- Laravel -->
                            <a href="https://laravel.com" target="_blank" rel="noopener noreferrer" 
                               class="group flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 rounded-lg border border-red-200 dark:border-red-800 hover:scale-105 transition-transform duration-200">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" viewBox="0 0 50 52" fill="currentColor">
                                    <path d="M49.626 11.564a.809.809 0 0 1 .028.209v10.972a.8.8 0 0 1-.402.694l-9.209 5.302V39.25c0 .286-.152.55-.4.694L20.42 51.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805 0 0 1-.41 0c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402 39.944A.801.801 0 0 1 0 39.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.802.802 0 0 1 .8 0l9.61 5.533h.002c.032.02.059.045.088.068.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809 0 0 1 .028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.801.801 0 0 1 .8 0l9.61 5.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068zm-1.574 10.718v-9.124l-3.363 1.936-4.646 2.675v9.124l8.01-4.611zm-9.61 16.505v-9.13l-4.57 2.61-13.05 7.448v9.216l17.62-10.144zM1.602 7.719v31.068L19.22 48.93v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-.002-21.481L4.965 9.654 1.602 7.72zm8.81-5.994L2.405 6.334l8.005 4.609 8.006-4.61-8.006-4.608zm4.164 28.764l4.645-2.674V7.719l-3.363 1.936-4.646 2.675v20.096l3.364-1.937zM39.243 7.164l-8.006 4.609 8.006 4.609 8.005-4.61-8.005-4.608zm-.801 10.605l-4.646-2.675-3.363-1.936v9.124l4.645 2.674 3.364 1.937v-9.124zM20.02 38.33l11.743-6.704 5.87-3.35-8-4.606-9.211 5.303-8.395 4.833 7.993 4.524z"/>
                                </svg>
                                <span class="font-semibold text-red-700 dark:text-red-300">Laravel</span>
                            </a>

                            <!-- Inertia.js -->
                            <a href="https://inertiajs.com" target="_blank" rel="noopener noreferrer"
                               class="group flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg border border-purple-200 dark:border-purple-800 hover:scale-105 transition-transform duration-200">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" viewBox="0 0 256 256" fill="currentColor">
                                    <path d="M128 0C57.308 0 0 57.308 0 128s57.308 128 128 128 128-57.308 128-128S198.692 0 128 0zm0 234.667C68.267 234.667 21.333 187.733 21.333 128S68.267 21.333 128 21.333 234.667 68.267 234.667 128 187.733 234.667 128 234.667zm64-106.667L128 64 64 128l64 64 64-64z"/>
                                </svg>
                                <span class="font-semibold text-purple-700 dark:text-purple-300">Inertia.js</span>
                            </a>

                            <!-- Tailwind CSS -->
                            <a href="https://tailwindcss.com" target="_blank" rel="noopener noreferrer"
                               class="group flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/20 dark:to-blue-900/20 rounded-lg border border-cyan-200 dark:border-cyan-800 hover:scale-105 transition-transform duration-200">
                                <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" viewBox="0 0 54 33" fill="currentColor">
                                    <path d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 33c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z"/>
                                </svg>
                                <span class="font-semibold text-cyan-700 dark:text-cyan-300">Tailwind CSS</span>
                            </a>
                        </div>

                        <!-- Additional Info -->
                        <div class="pt-4 border-t border-gray-200 dark:border-slate-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Powered by Laravel Reverb for real-time WebSocket communication
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, computed, onUnmounted } from 'vue';
import CaptchaModal from '@/Components/CaptchaModal.vue';

const props = defineProps({
    recent: Array,
    sessionId: String,
});

const form = useForm({
    url: '',
    realTime: false,
    captcha_token: '',
});

const results = ref(null);
const resultsHash = ref(null);
const analyzing = ref(false);
const loadingMessage = ref('Initializing health check...');

const loadingMessages = [
    'Initializing health check...',
    'Analyzing HTTP response...',
    'Checking SSL certificate...',
    'Resolving DNS records...',
    'Scanning security headers...',
    'Running Lighthouse audit...',
    'Measuring Core Web Vitals...',
    'Checking for broken links...',
    'Compiling results...',
];

let messageInterval = null;
let messageIndex = 0;

// ── CAPTCHA gate ───────────────────────────────────────────
const showCaptcha = ref(false);

/** Called when the Analyze button is clicked — opens CAPTCHA first */
const handleAnalyzeClick = () => {
    if (!form.url) return;
    showCaptcha.value = true;
};

/** CAPTCHA passed — now do the real submission */
const onCaptchaVerified = () => {
    showCaptcha.value = false;
    form.captcha_token = 'verified_human';
    submit();
};

/** User closed/cancelled the CAPTCHA */
const onCaptchaCancel = () => {
    showCaptcha.value = false;
};

/** Internal: actually post the form (only reachable after CAPTCHA) */
const submit = () => {
    analyzing.value = true;
    results.value = null;
    messageIndex = 0;
    loadingMessage.value = loadingMessages[0];

    // Cycle through loading messages
    messageInterval = setInterval(() => {
        messageIndex = (messageIndex + 1) % loadingMessages.length;
        loadingMessage.value = loadingMessages[messageIndex];
    }, 3000);

    form.post('/check', {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            // Reset token so they have to solve CAPTCHA again next time
            form.captcha_token = '';
        },
        onFinish: () => {
            // Analyzing stays true until WebSocket event is received
        }
    });
};

const hasMetrics = computed(() => {
    if (!results.value || !results.value.metrics) return false;
    return Object.values(results.value.metrics).some(v => v !== null);
});

onMounted(() => {
    if (props.sessionId && window.Echo) {
        window.Echo.channel(`health-check.${props.sessionId}`)
            .listen('HealthCheckUpdated', (e) => {
                results.value = e.results;
                resultsHash.value = e.urlHash;
                analyzing.value = false;
                if (messageInterval) {
                    clearInterval(messageInterval);
                    messageInterval = null;
                }
            })
            .error((error) => {
                console.error('Echo error:', error);
                analyzing.value = false;
                if (messageInterval) {
                    clearInterval(messageInterval);
                    messageInterval = null;
                }
            });
    } else {
        console.warn('Echo not available or sessionId missing');
    }
});

onUnmounted(() => {
    if (messageInterval) {
        clearInterval(messageInterval);
    }
});

// Child Components - Using h() render function instead of template strings
import { h } from 'vue';

const MetricCard = {
    props: ['title', 'status', 'icon', 'delay'],
    setup(props, { slots }) {
        return () => h('div', {
            class: [
                'bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-lg rounded-xl p-6 border-2 transition-all duration-300 hover:shadow-2xl hover:scale-105 animate-slide-up',
                {
                    'border-green-200 dark:border-green-800': props.status === 'success',
                    'border-red-200 dark:border-red-800': props.status === 'error',
                    'border-yellow-200 dark:border-yellow-800': props.status === 'warning',
                    'border-gray-200 dark:border-slate-600': props.status === 'neutral'
                }
            ],
            style: props.delay ? `animation-delay: ${props.delay}ms` : ''
        }, [
            h('h4', { class: 'text-sm font-semibold text-gray-600 dark:text-gray-400 mb-4' }, props.title),
            slots.default()
        ]);
    }
};

const ScoreCircle = {
    props: ['label', 'score', 'delay'],
    setup(props) {
        const circumference = 251.2;
        const offset = circumference - (circumference * props.score / 100);
        const colorClass = props.score >= 90 ? 'text-green-500' : props.score >= 50 ? 'text-yellow-500' : 'text-red-500';
        const textColorClass = props.score >= 90 ? 'text-green-600 dark:text-green-400' : props.score >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400';

        return () => h('div', { 
            class: 'flex flex-col items-center animate-scale-in',
            style: props.delay ? `animation-delay: ${props.delay}ms` : ''
        }, [
            h('div', { class: 'relative w-24 h-24 mb-2' }, [
                h('svg', { class: 'transform -rotate-90 w-24 h-24' }, [
                    h('circle', {
                        cx: '48',
                        cy: '48',
                        r: '40',
                        stroke: 'currentColor',
                        'stroke-width': '8',
                        fill: 'none',
                        class: 'text-gray-200 dark:text-slate-700'
                    }),
                    h('circle', {
                        cx: '48',
                        cy: '48',
                        r: '40',
                        stroke: 'currentColor',
                        'stroke-width': '8',
                        fill: 'none',
                        'stroke-dasharray': circumference,
                        'stroke-dashoffset': offset,
                        class: [colorClass, 'transition-all duration-1000 ease-out']
                    })
                ]),
                h('div', { class: 'absolute inset-0 flex items-center justify-center' }, [
                    h('span', { class: ['text-2xl font-bold', textColorClass] }, Math.round(props.score))
                ])
            ]),
            h('span', { class: 'text-sm font-medium text-gray-700 dark:text-gray-300' }, props.label)
        ]);
    }
};

const MetricBadge = {
    props: ['label', 'value', 'description', 'delay'],
    setup(props) {
        return () => h('div', {
            class: 'p-4 bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-lg border border-purple-100 dark:border-purple-800 hover:scale-105 transition-transform duration-200 animate-slide-up',
            style: props.delay ? `animation-delay: ${props.delay}ms` : ''
        }, [
            h('div', { class: 'text-xs font-semibold text-purple-600 dark:text-purple-400 mb-1' }, props.label),
            h('div', { class: 'text-2xl font-bold text-gray-900 dark:text-white mb-1' }, props.value),
            h('div', { class: 'text-xs text-gray-600 dark:text-gray-400' }, props.description)
        ]);
    }
};

const SecurityHeader = {
    props: ['name', 'info', 'delay'],
    setup(props) {
        const isPass = props.info.status === 'pass';
        return () => h('div', {
            class: [
                'p-4 rounded-lg border-2 transition-all duration-200 hover:scale-[1.02] animate-slide-in-right',
                isPass ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800'
            ],
            style: props.delay ? `animation-delay: ${props.delay}ms` : ''
        }, [
            h('div', { class: 'flex items-start justify-between' }, [
                h('div', { class: 'flex-1' }, [
                    h('div', { class: 'flex items-center' }, [
                        h('span', {
                            class: ['text-sm font-semibold', isPass ? 'text-green-900 dark:text-green-300' : 'text-red-900 dark:text-red-300']
                        }, props.name),
                        h('span', {
                            class: ['ml-2 text-xs px-2 py-1 rounded-full', isPass ? 'bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200' : 'bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200']
                        }, isPass ? 'Present' : 'Missing')
                    ]),
                    h('p', {
                        class: ['text-xs mt-2', isPass ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400']
                    }, props.info.recommendation)
                ])
            ])
        ]);
    }
};
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-in-right {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes scale-in {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@keyframes scan {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes progress {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.6s ease-out;
}

.animate-slide-in-right {
    animation: slide-in-right 0.5s ease-out;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

.animate-scan {
    animation: scan 2s linear infinite;
}

.animate-progress {
    animation: progress 2s ease-in-out infinite;
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}

.animate-spin-slow {
    animation: spin-slow 8s linear infinite;
}

.delay-100 {
    animation-delay: 100ms;
}

.delay-200 {
    animation-delay: 200ms;
}

.delay-300 {
    animation-delay: 300ms;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
