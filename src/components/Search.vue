<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <label for="sel1">
          <b>From Year:
            <animated-number :value="year" :formatValue="e=>+e.toFixed(0)" :duration="500"/>
          </b>
        </label>
        <select @change="changeSelectHandler" v-model="year" class="form-control" id="sel1">
          <option v-for="y in yearsArr" :key="y" :value="y">{{y}}</option>
        </select>
      </div>
      <div class="col">
        <label for="sel2">
          <b>Month:
            <transition-group name="slide-fade" tag="span">
              <span :key="1" v-if="animate_text_stage">{{monthComp}}</span>
              <span :key="2" v-if="!animate_text_stage">{{monthComp}}</span>
            </transition-group>
          </b>
        </label>
        <select @change="changeSelectHandler" v-model="month" class="form-control" id="sel3">
          <option v-for="(m,i) in months" :key="m" :value="i">{{m}}</option>
        </select>
      </div>
      <div class="col">
        <label for="sel3">
          <b>
            Years back:
            <animated-number :value="back" :formatValue="e=>+e.toFixed(0)" :duration="500"/>
            <animated-number :value="year-back" :formatValue="e=>' (' + +e.toFixed(0)" :duration="500"/>)
          </b>
        </label>
        <select @change="changeSelectHandler" v-model="back" class="form-control" id="sel3">
          <option
            v-for="(e,i) in new Array(5).fill(1)"
            :key="++i"
            :value="i"
          >{{i}} year{{i>1?'s':''}}</option>
        </select>
      </div>
      <div class="col">
        <label for="sel4">
          <b>Detentions:
            <animated-number :value="dets" :formatValue="e=>+e.toFixed(0)" :duration="500"/>
            {{dets?'or more':''}}
          </b>
        </label>
        <select @change="changeSelectHandler" v-model="dets" class="form-control" id="sel4">
          <option v-for="d in detsArr" :key="d">{{d}}</option>
        </select>
      </div>
      <div class="col middle">
        <button
          v-if="load==0"
          type="button"
          :class="{block_btn, btn_canceled}"
          class="btn btn-warning"
          @click="parseData"
        >
          <img v-if="isDevMode" src="../assets/google.gif">
          <img v-else :src="props_data.assets_path+'google.gif'">
          {{search_text}}
        </button>
        
        <button
          v-else
          type="button"
          :class="block_btn"
          class="btn btn-danger btn-cancel"
          @click="cancelHandler"
        >
          <!-- <i class="fa fa-spinner fa-spin"></i>-->
          <img v-if="isDevMode" src="../assets/spinner.gif">
          <img v-else :src="props_data.assets_path+'spinner.gif'">
          Cancel
        </button>

        <b-form-checkbox-group v-model="selected" name="flavour1" :options="options" v-b-tooltip.html.bottom :title="`Automatic update of lists after changing search parameters`"></b-form-checkbox-group>
      </div>
    </div>
  </div>
</template>  

 <script>


 import axios from 'axios';
import { cacheAdapterEnhancer } from 'axios-extensions';



export default {
  props: {
    props_data: {
      type: Object,
      default: () => {
        return {
          api_url: "/api.php?",
          assets_path: process.env.BASE_URL // "../assets/"
        };
      }
    }
  },
  data() {
    return {
      selected: [], // Must be an array reference!
      options: ["automatic"],

      dets: 2,
      load: 0,
      cancel_txt: "Canceled!",
      search_text: "Search",
      search_text_init: "Search", 
      btn_canceled: "",
      block_btn: "", // .block_btn { /*css*/}
      year: new Date().getFullYear(),
      month: new Date().getMonth() + 1,
      back: 1,

      animate_text_stage: true,

      cancel_req_token: null,
      months: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
      ]
    };
  },
  mounted() {
    this.month = new Date().getMonth(); 


 
//axios.defaults.baseURL = process.env.BASE_URL;  
//axios.defaults.headers.get['Accepts'] = 'application/json';
//axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*'; 
//axios.defaults.headers.common['Access-Control-Allow-Headers'] = 'Origin, X-Requested-With, Content-Type, Accept';

window.http = axios.create({
    baseURL: '/',
    //headers: { 'Cache-Control': 'no-cache' },
    // cache will be enabled by default
    adapter: cacheAdapterEnhancer(axios.defaults.adapter)
});

/*
http.get('https://my-json-server.typicode.com/typicode/demo/posts?1'); // make real http request
http.get('https://my-json-server.typicode.com/typicode/demo/posts?1'); // use the response from the cache of previous request, without real http request made
http.get('https://my-json-server.typicode.com/typicode/demo/posts?1', { cache: false }); // disable cache manually and the the real http request invoked
*/

  },
  watch: {
    month(neww, old) {
      if (neww != old) this.animate_text_stage = !this.animate_text_stage;
    },
    selected(neww, old) {
      if (neww.length) this.parseData();
    }
  },
  methods: {
    changeSelectHandler() {
      if (this.selected.includes(this.options[0])) this.parseData();
    },
    cancelHandler() {
      this.cancel_req_token
        ? this.cancel_req_token.cancel("Операция отменена пользователем")
        : "";
      this.cancel_req_token = null;
    },
    changeBtnTextTemp(text) {
      this.block_btn = "block_btn";
      let old = this.search_text;
      if (old == text) return;
      this.search_text = text;

      setTimeout(() => {
        this.search_text = this.search_text_init;//old;
        
        this.block_btn = "";
        this.btn_canceled = "";
      }, 1000);
    },
    parseData: function() {
      this.load = 1;
      //let axios = this.$http;

      const CancelToken = axios.CancelToken;
      const source = CancelToken.source();
      this.cancel_req_token ? this.cancel_req_token.cancel() : "";
      this.cancel_req_token = source;
      let options = {
        cancelToken: source.token,
        params: {
          Year: this.year,
          Month: this.month + 1,
          back: this.back,
          dets: this.dets
        }
      };
      http
        .get(this.props_data.api_url, options)
        .then(res => {
          window.otv = res;
          let d = res.data
          if(typeof d == 'undefined' || !(d instanceof Object && Object.keys(d).includes('watch_list') && Object.keys(d).includes('perf_list'))) { // d = {watch:[{},{}],perf_list:[{},{}]}
            this.btn_canceled = "btn_canceled";
            throw ('Erorr');
          }

          var k = "HUID"; // group by;

          let under_perfomance = d.perf_list.reduce((map, obj) => {
            //if(!obj.selected) {    return map;  }
            let makeCode = (map[obj[k]] = map[obj[k]] || {}); // var modelCode = makeCode[obj.HUID] = makeCode[obj.HUID] || { count: 0 };
            let l = map[obj[k]];
            let m = Object.keys(l).length;
            l[m] = { ...obj };
            return map;
          }, {});
          
          this.$emit("response", { watch_list: d.watch_list, under_perfomance });
          this.load = 0;
          if ((typeof d.watch_list == 'object' && d.watch_list.length>0) || (typeof under_perfomance == 'object' && under_perfomance.length>0))
          this.changeBtnTextTemp("Success!");
          else this.changeBtnTextTemp("No results");
        })
        .catch(e => {
          if (axios.isCancel(e)) {
            console.log("[GET] => Request canceled", e, e.message);
            this.btn_canceled = "btn_canceled";
            this.changeBtnTextTemp(this.cancel_txt);
          } else {
            console.warn("[GET] handle error ->", e);
            this.changeBtnTextTemp("Error");
          }
          this.load = 0;
        });
    }
  },
  computed: {
    isDevMode() {
      return process.env.NODE_ENV === "development";
    },
    yearsArr() {
      let d = new Date().getFullYear();
      // return new Array(13).fill(1).map((e, i) => (i == 0 ? "" : d--));//селект с пустой опцией
      return new Array(13).fill(1).map(() => d--);
    },
    detsArr() {
      //   return new Array(12).fill(1).map((e, i) => (i == 0 ? "" : --i)); // ['',0,1...,10];//селект с пустой опцией
      return new Array(11).fill(1).map((e, i) => i); // [0,1...,10];//селект
    },
    monthComp() {
      let M = +this.month; //let arr = this.months.filter((e,ee)=>Object.keys(e)[0] == this.Month); //return arr.length>0?arr[M]:'';
      return this.months[M];
    }
  }
};
</script> 
<style>
/* Анимации появления и исчезновения могут иметь */
/* различные продолжительности и динамику.       */
.slide-fade-enter-active {
  /*transition: all 0.01s ease;*/
}
.slide-fade-leave-active {
  /*transition: all 0.01s cubic-bezier(1, 0.5, 0.8, 1);*/
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active до версии 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
</style>
 <style scoped>
.middle {
  display: flex;
  flex-direction: column;
  align-items: center;
  align-self: flex-end;
}
img {
  width: 18px;
  vertical-align: text-top;
}
.btn {
  width: 130px;
  margin-top: 0px;
}

.btn-cancel {
  background: #4caf50;
}

.btn_canceled {
  background: darkred;
  color: white;
}

.block_btn {
  pointer-events: none;
}
</style>
 
