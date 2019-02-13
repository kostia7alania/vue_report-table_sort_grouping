<template>
  <footer id="footer" class="footer">
    <!--Copyright-->
    <div class="container footer-copyright">
      <div
        class="container-fluid text-muted"
      >© Hosted by <span class="footer-full">Information and Coordinating Center on Port and Flag State Control</span><span  class="footer-short">ICCPFSC</span> . Moscow, Russia, {{(new Date).getFullYear()}}</div>
    </div>
  </footer>
</template>  
 <script>
export default {};
</script>
 <style>
 
@media (min-width: 1000px) {
  .footer-full  { display: inline-block;}
  .footer-short { display: none; }
}
@media (max-width: 860px) {
  .footer-full  { display: none; }
  .footer-short { display: inline-block;}
}


.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: #ddd;
  text-align: center;
}
</style>