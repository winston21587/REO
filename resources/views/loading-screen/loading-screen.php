<?php ?>

<style>
    body { margin: 0; padding: 0; }
    #loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: white; display: flex; align-items: center; justify-content: center; z-index: 9999; }
    #loader.fade-out { animation: loaderFadeOut 0.4s ease-out forwards; }
    #content { display: none; opacity: 0; }
    #content.fade-in { animation: contentFadeIn 0.4s ease-in forwards; }

    .container {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .loader {
      position: relative;
      width: 158px;
      height: 158px;
      perspective: 800px;
    }

    .crystal {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 47px;
      height: 47px;
      opacity: 0;
      transform-origin: bottom center;
      transform: translate(-50%, -50%) rotateX(45deg) rotateZ(0deg);
      animation: spin 4s linear infinite, emerge 2s ease-in-out infinite alternate,
        fadeIn 0.3s ease-out forwards;
      border-radius: 10px;
      visibility: hidden;
    }

    @keyframes spin {
      from {
        transform: translate(-50%, -50%) rotateX(45deg) rotateZ(0deg);
      }
      to {
        transform: translate(-50%, -50%) rotateX(45deg) rotateZ(360deg);
      }
    }

    @keyframes emerge {
      0%,
      100% {
        transform: translate(-50%, -50%) scale(0.5);
        opacity: 0;
      }
      50% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
      }
    }

    @keyframes fadeIn {
      to {
        visibility: visible;
        opacity: 0.8;
      }
    }

    @keyframes loaderFadeOut {
      from {
        opacity: 1;
      }
      to {
        opacity: 0;
      }
    }

    @keyframes contentFadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    .crystal:nth-child(1) {
      background: linear-gradient(45deg, #550000, #770000);
      animation-delay: 0s;
    }

    .crystal:nth-child(2) {
      background: linear-gradient(45deg, #770000, #880000);
      animation-delay: 0.3s;
    }

    .crystal:nth-child(3) {
      background: linear-gradient(45deg, #880000, #990000);
      animation-delay: 0.6s;
    }

    .crystal:nth-child(4) {
      background: linear-gradient(45deg, #990000, #aa0000);
      animation-delay: 0.9s;
    }

    .crystal:nth-child(5) {
      background: linear-gradient(45deg, #aa0000, #bb0000);
      animation-delay: 1.2s;
    }

    .crystal:nth-child(6) {
      background: linear-gradient(45deg, #bb0000, #cc0000);
      animation-delay: 1.5s;
    }
</style>

<div id="loader">
    <div class="container">
        <div class="loader">
            <div class="crystal"></div>
            <div class="crystal"></div>
            <div class="crystal"></div>
            <div class="crystal"></div>
            <div class="crystal"></div>
            <div class="crystal"></div>
        </div>
    </div>
</div>

<script>
    let loaderMinTime = 3000; // 3 Seconds Loading Screen Animation
    let pageLoadTime = Date.now();
    let loaderHidden = false;

    function hideLoader() {
        if (!loaderHidden) {
            let loader = document.getElementById('loader');
            let content = document.getElementById('content');
            
            if (loader) {
                loader.classList.add('fade-out');
            }
            
            if (content) {
                content.style.display = 'block';
                content.classList.add('fade-in');
            }
            
            setTimeout(() => {
                if (loader) loader.style.display = 'none';
                loaderHidden = true;
            }, 400); // 0.4  seconds for the fade effect before mag load ang website
        }
    }

    window.addEventListener('load', function() {
        let elapsedTime = Date.now() - pageLoadTime;
        let remainingTime = loaderMinTime - elapsedTime;
        
        if (remainingTime > 0) {
            setTimeout(hideLoader, remainingTime);
        } else {
            hideLoader();
        }
    });

    setTimeout(hideLoader, 5000); // fallback feature, iwas mag-tagal ang loading screen, 5 seconds maxiumum 
</script>