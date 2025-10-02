  <div class="faq-wrapper">
      <div class="faq-container max-w-[900px] mx-auto p-[20px]">
          <h2 class="text-[2rem] font-[700] mb-[24px]">Frequently Asked Questions</h2>
    
          <div class="faq-item mb-[12px]">
              <div class="faq-question font-[600] cursor-pointer flex justify-between">QUESTION TEXT SAMPLE <span>+</span></div>
              <div class="faq-answer">FAQ ANSWER TEXT SAMPLE</div>
          </div>
        </div>
    </div>

<script src="./js/faqrh.js">
  document.addEventListener("DOMContentLoaded", function () {
    const faqItems = document.querySelectorAll(".faq-container .faq-item");

    faqItems.forEach((item) => {
        const question = item.querySelector(".faq-question");
        const answer = item.querySelector(".faq-answer");
        const toggleSymbol = item.querySelector(".faq-toggle");

        question.addEventListener("click", () => {
            const isVisible = answer.style.display === "block";

            document.querySelectorAll(".faq-container .faq-answer").forEach((ans) => {
                ans.style.display = "none";
            });

            document.querySelectorAll(".faq-container .faq-toggle").forEach((symbol) => {
                symbol.textContent = "+";
            });

            answer.style.display = isVisible ? "none" : "block";
            toggleSymbol.textContent = isVisible ? "+" : "-";
        });
    });
});


const items = document.querySelectorAll(".accordion button");

function toggleAccordion() {
  const itemToggle = this.getAttribute('aria-expanded');
  
  for (i = 0; i < items.length; i++) {
    items[i].setAttribute('aria-expanded', 'false');
  }
  
  if (itemToggle == 'false') {
    this.setAttribute('aria-expanded', 'true');
  }
}

items.forEach(item => item.addEventListener('click', toggleAccordion));
</script>