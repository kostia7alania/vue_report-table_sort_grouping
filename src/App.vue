<template>
<!--<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> 
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script>-->
  <div id="app2">
    <b-jumbotron bg-variant="default" text-variant="dark" border-variant="default" :header="props_data.title" :lead="props_data.title2">
      <br> <br>
    <Search :props_data="props_data" @response="responseHandler"/>

    </b-jumbotron>

  
    <Result :responce="responce"/>
    <Footer/>
  </div>
</template>

<script>
import Search from "./components/Search.vue";
import Result from "./components/Result.vue";
import Footer from "./components/Footer.vue";

export default {
  name: "app",
  props: ['props_data'],
  data() {
    return { responce: {} }
  },
  components: { Search, Result, Footer },
  methods: {
    responseHandler() {
      //console.log('==>>', arguments);
      this.responce=arguments[0];
    }
  }
};
</script>

<style> 
* {
  box-sizing: border-box;
} 
body {
  background-color: #f5f6f7; 
} 
#app2 {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 0px;
  max-width: 2500px; 
}
button,select {
  cursor: pointer;
  transition: all 1s ease ;
}
button:hover, select:hover {
  transform: scale(1.1)
}
* width */
::-webkit-scrollbar {
  width: 20px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: red; 
  border-radius: 10px;
}


body::-webkit-scrollbar {
    width: 1em;
}
 
body::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}
 
body::-webkit-scrollbar-thumb {
  background-color: black;
  outline: 1px solid slategrey;
}
</style>
