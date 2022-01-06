<template>
    <div class="backoffice-container">
        <div class="menu">
            <Menu></Menu>
        </div>
        <div class="section">
            <h2>Tasks</h2>
            <Tasks></Tasks>
        </div>
    </div>
</template>

<script>
    import Menu from "../components/menu";
    import Tasks from "../components/tasks/grid";
    export default {
        components: {
            Menu,
            Tasks
        }
    }
</script>

<style>
 .backoffice-container {
    display: flex;
     height: 100%;
 }
 .backoffice-container .menu {
    min-width: 200px;
 }
 .backoffice-container .section {
    flex-grow: 1;
    overflow: auto;
     padding: 10px;
 }
 .backoffice-container .section > h2 {
     text-align: center;
     padding-top: 10px;
 }
</style>
