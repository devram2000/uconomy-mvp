<div>
<main>
    <section id="heading">
      <section id="heading-text">
        <section id="headline">
          <h1> <mark class="pc">Gain control</mark> over your bill due dates.</h1>
        </section>
        <section id="heading-form">            

            <button wire:click="redirectSignup" href="/register" class="button2 button-text" id="submit" name="join">Join Today</button>
        


          </form>
        </section>
        <!--<section id="subheadline" class="pcl1 waitlist">-->
        <!--  Join us and get approved for $250  -->
        <!--</section>-->
      </section>
      <img class="lazy widget"  src="https://dummyimage.com/500x500/ffffff/fff" data-src="/storage/home-photos/Hero.webp" id="hero-image" alt="Phone Calendar Icon" height="500" width="500">
    </section>
    <section id="banner">
       <h2 class="flexibility"> You deserve the flexibility to choose what works best for you, so why wait?  </h2>
    </section>
    <section id="benefits">
      <section class="benefit">
        <img class="lazy widget"  src="https://dummyimage.com/500x500/ffffff/fff" data-src="/storage/home-photos/Pay.webp" id="sales-image" alt="Pay When You Want Icon" height="500" width="500">
        <div class="benefit-text">
        <div class="center"><h2 class="pcl1">Pay When You Can</h2></div>   <br />
          <h3 class="gd">Tired of fixed payment schedules and missed deadlines? Say goodbye to stress and hello to a payment plan that fits your life. Take control of your payments today! 
 </h3>
        </div>     
      </section>

      <section class="benefit reverse">
        <img class="lazy widget" src="https://dummyimage.com/500x500/ffffff/fff" data-src="/storage/home-photos/Use.webp" alt="Use Us Anywhere Icon" height="500" width="500" >
        <div class="benefit-text"> 
        <div class="center"><h2 class="pcl1">Use Us For Any Bills</h2></div>   <br />
          <h3 class="gd">We’ve got you covered for credit cards, rent, utilities, and any other bills. Our all in one payment system helps streamline your finances and stay on top of your bills with ease. </h3>
        </div> 
      </section>

      <section class="benefit">
        <img class="lazy widget" src="https://dummyimage.com/500x500/ffffff/fff" data-src="/storage/home-photos/Credit.webp" alt="Secure Transactions Icon" id="secure-image" height="500" width="500" >
        <div class="benefit-text"> 
          <div class="center"> <h2 class="pcl1">No Extra Work For You</h2></div>  <br />
          <h3 class="gd">All you need to do is upload a photo of your bill, and we'll take care of the rest. If we can't change your dates, you won't pay anything out of pocket.</h3>
        </div> 
      </section>

    </section>
  </main>
  <footer>
    <div id="footer-content">

      <div id="socials">
        <div>
          <a class="scroll" href="#top"><img class="logo" src="/storage/home-photos/Logo.svg" alt="Uconomy Logo" width="196" height="45.5"></a>
          <div id="agreements" >
            <div><a wire:click="redirectPrivacy" href="/privacy-policy"><div class="b1">Privacy Policy</div></a></div>
            <div><a wire:click="redirectTerms" href="/terms-of-service"><div class="b1">Terms of Service</div></a></div>
          </div>
        </div>
        <div class="s1" >
          © 2022 Copyright Uconomy Inc.
        </div>
      </div>
      
      <div id="footer-cta">
        <div class="b3">Still have questions? <a href="mailto:help@uconomy.com"><i>Contact us</i></a>!</div>
        <section id="footer-buttons">
             
        <button wire:click="redirectSignup" href="/register" class="button2 button-text" id="submit" name="join">Join Today</button>
             


        </section>

      </div>
    </div>

  </footer>

  <script>
          window.addEventListener('scroll', function () {
              let header = document.querySelector('header');
              let windowPosition = window.scrollY > 0;
              header.classList.toggle('scrolling-active', windowPosition);
          })
          
  </script>

  <script>
        document.addEventListener("DOMContentLoaded", function() {
      var lazyloadImages;    

      if ("IntersectionObserver" in window) {
        lazyloadImages = document.querySelectorAll(".lazy");
        var imageObserver = new IntersectionObserver(function(entries, observer) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              var image = entry.target;
              image.src = image.dataset.src;
              image.classList.remove("lazy");
              imageObserver.unobserve(image);
            }
          });
        });

        lazyloadImages.forEach(function(image) {
          imageObserver.observe(image);
        });
      } else {  
        var lazyloadThrottleTimeout;
        lazyloadImages = document.querySelectorAll(".lazy");
        
        function lazyload () {
          if(lazyloadThrottleTimeout) {
            clearTimeout(lazyloadThrottleTimeout);
          }    

          lazyloadThrottleTimeout = setTimeout(function() {
            var scrollTop = window.pageYOffset;
            lazyloadImages.forEach(function(img) {
                if(img.offsetTop < (window.innerHeight + scrollTop)) {
                  img.src = img.dataset.src;
                  img.classList.remove('lazy');
                }
            });
            if(lazyloadImages.length == 0) { 
              document.removeEventListener("scroll", lazyload);
              window.removeEventListener("resize", lazyload);
              window.removeEventListener("orientationChange", lazyload);
            }
          }, 20);
        }

        document.addEventListener("scroll", lazyload);
        window.addEventListener("resize", lazyload);
        window.addEventListener("orientationChange", lazyload);
      }
    })


  </script>

</div>
