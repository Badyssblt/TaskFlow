
export default defineNuxtPlugin((nuxtApp) => {
    const extractId = (iri) => {
        const match = iri.match(/\/(\d+)(?!.*\/\d+)/);
        return match ? parseInt(match[1]) : null;
    }

    nuxtApp.provide("extractId", extractId);
})