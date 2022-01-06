<template>
    <div class="menu-container">
        <div class="menu-title">
            <a href="/">
                <i class="bi bi-gear"></i>
                <span>BM Backoffice</span>
            </a>
        </div>
        <nav>
            <a href="/tasks">
                <i class="bi bi-list-task"></i>
                <span>Tasks</span>
            </a>
        </nav>
    </div>
</template>

<script>
export default {}
</script>

<style>
.menu-container {
    text-align: center;
    background-color: rgb(4, 35, 49);
    color: white;
    height: 100%;
}

.menu-title {
    font-size: 1.5rem;
    font-family: 'system-ui';
    padding: 15px;
}

.menu-title i {
    vertical-align: baseline;
}

.menu-container a {
    color: white;
    text-decoration: none;
}

.menu-container a i {
    vertical-align: middle;
}
</style>
