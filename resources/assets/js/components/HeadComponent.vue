<template>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <router-link to="/" class="navbar-item">
                Upload Your Photo
            </router-link>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div class="navbar-menu">
            <router-link to="/photos" class="navbar-item">Photos</router-link>
            <router-link to="/about" class="navbar-item">About</router-link>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "HeadComponent"
    }
</script>
