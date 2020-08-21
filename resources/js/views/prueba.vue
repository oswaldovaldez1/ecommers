<template>
    <p>{{datos}}</p>
</template>
<script>
export default {
     computed: {
         datos(){
             return this.$route.params.id;
         }
     }
}
</script>