// main variables
const text = "Welcome to Our Story";
const speed = 80;
const eraseSpeed = 50;
const delay = 1000;
let index = 0; // the pointer of the current letter that will be shown
let isDeleting = false;

function loopTyping() {
  // getter that will get the h1
  const element = document.getElementById("typing-text");

  if (!isDeleting) {
    // extract some of string and insert it into HTML
    element.textContent = text.substring(0, index + 1);
    index++;

    if (index === text.length) {
      isDeleting = true;
      // it makes a delay
      setTimeout(loopTyping, delay);
      // to stop continue of the function in same calling
      return;
    }

    // Deleting Section
  } else {
    element.textContent = text.substring(0, index - 1);
    index--;

    if (index === 0) {
      isDeleting = false;
    }
  }
  // to call the function many times
  setTimeout(loopTyping, isDeleting ? eraseSpeed : speed);
}

// the code runs when the page reloads
window.onload = loopTyping;
