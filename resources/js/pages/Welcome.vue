<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="relative flex min-h-screen flex-col items-center overflow-hidden bg-[radial-gradient(circle_at_top,_#f3efe4,_#fdfdfc_55%)] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[radial-gradient(circle_at_top,_#1b2a2c,_#0a0a0a_55%)]">
        <div
            class="absolute inset-x-0 top-0 h-72 bg-[linear-gradient(135deg,_rgba(32,106,93,0.18),_rgba(212,168,87,0.08))] blur-3xl">
        </div>
        <div class="absolute -left-16 top-24 h-40 w-40 rounded-full bg-[#d0e3dc] opacity-60 blur-3xl dark:bg-[#16352f]">
        </div>
        <div
            class="absolute -right-12 bottom-16 h-56 w-56 rounded-full bg-[#ead7a1] opacity-40 blur-3xl dark:bg-[#5b4a1f]">
        </div>
        <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
        </header>
        <div
            class="relative flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
            <main
                class="w-full max-w-[335px] overflow-hidden rounded-[2rem] border border-white/70 bg-white/85 shadow-[0_30px_80px_rgba(27,27,24,0.12)] backdrop-blur lg:max-w-4xl xl:max-w-5xl dark:border-white/10 dark:bg-white/5 dark:shadow-[0_30px_80px_rgba(0,0,0,0.35)]">
                <section class="relative flex flex-col gap-6 px-7 py-10 sm:px-10 lg:px-12 lg:py-14">
                   
                    <div class="space-y-4">
                        
                        <h1
                            class="max-w-2xl text-2xl font-semibold leading-tight text-[#14211d] sm:text-2xl lg:text-4xl dark:text-[#f4f0e8]">
                            Sistema de monitorización remota de la humedad del suelo
                        </h1>
                        <p class="max-w-xl text-sm leading-7 text-[#4b544e] sm:text-base dark:text-[#c7cec9]">
                            Supervisión de lecturas en tiempo real, detección de alertas tempranas y centralización de datos para una gestión eficiente del riego y el cultivo.
                        </p>
                    </div>
                    <div
                        class="h-px w-full bg-gradient-to-r from-[#206a5d]/40 via-[#d4a857]/45 to-transparent dark:from-[#8dd3bb]/40 dark:via-[#d7bc75]/45">
                    </div>
                    <div class="grid gap-3 text-sm text-[#31403a] sm:grid-cols-2 dark:text-[#d7ddd9]">
                        <div
                            class="rounded-2xl border border-black/5 bg-black/[0.02] p-4 dark:border-white/10 dark:bg-white/[0.03]">
                            <p class="text-xs uppercase tracking-[0.22em] text-[#8b6b2e] dark:text-[#d7bc75]">Programa
                            </p>
                            <p class="mt-2 text-base font-medium">Ingeniería en Telemática</p>
                        </div>
                        <div
                            class="rounded-2xl border border-black/5 bg-black/[0.02] p-4 dark:border-white/10 dark:bg-white/[0.03]">
                            <p class="text-xs uppercase tracking-[0.22em] text-[#8b6b2e] dark:text-[#d7bc75]">
                                Institución</p>
                            <p class="mt-2 text-base font-medium">Universidad Distrital Francisco José de Caldas</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <Link v-if="$page.props.auth.user" :href="dashboard()"
                            class="inline-flex items-center justify-center rounded-full bg-[#1f5c52] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#17463f] dark:bg-[#8dd3bb] dark:text-[#10201c] dark:hover:bg-[#74c2a8]">
                            Ir al panel
                        </Link>
                        <template v-else>
                            <Link :href="login()"
                                class="inline-flex items-center justify-center rounded-full bg-[#1f5c52] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#17463f] dark:bg-[#8dd3bb] dark:text-[#10201c] dark:hover:bg-[#74c2a8]">
                                Iniciar sesión
                            </Link>
                        </template>
                    </div>
                </section>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
