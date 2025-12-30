// getters
const testimonials = document.querySelectorAll('.testimonial');
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');
let current = 0; // 0 , 1 , 2

// Showing the next pictures by for each
function showTestimonial(index) {
  testimonials.forEach((t, i) => {
    t.classList.remove('active');
    if (i === index) t.classList.add('active');
  });
}
// Next Button (% it makes loop around  testimonial.length ----> it makes never go above 2  )
nextBtn.addEventListener('click', () => {
  current = (current + 1) % testimonials.length;
  showTestimonial(current);
});


// Previous Button
prevBtn.addEventListener('click', () => {
  current = (current - 1 + testimonials.length) % testimonials.length;
  showTestimonial(current);
});

// Auto slide every 5 seconds
setInterval(() => {
  current = (current + 1) % testimonials.length;
  showTestimonial(current);
}, 5000);
