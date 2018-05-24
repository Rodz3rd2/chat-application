module.exports = {
    debug: false,

    sass: {
        command: "sass",
        watch_command: "sass:watch",
        dir: "resources/assets/sass",
        dest: "public/css",
        sources: ["**/*.scss"]
    },

    scripts: {
        command: "scripts",
        watch_command: "scripts:watch",
        dir: "resources/assets/js",
        dest: "public/js",
        sources: ["**/*.js"]
    },

    build: {
        views: {
            command: "build:views",
            dir: "resources/views",
            sources: [
                "**/*.html",
                "**/*.twig"
            ],
            search_path: "public",
            dest: ""
        },

        images: {
            command: "build:images",
            dir: "public",
            sources: [
                "**/*.jpg",
                "**/*.png",
                "**/*.gif"
            ],
            flatten: false,
            dest: "img"
        },

        fonts: {
            command: "build:fonts",
            dir: "public",
            flatten: true,
            sources: [
                "**/*.eot",
                "**/*.svg",
                "**/*.ttf",
                "**/*.woff",
                "**/*.woff2",
                "**/*.otf"
            ],
            dest: "fonts"
        },

        dist: "resources/dist-views"
    }
};