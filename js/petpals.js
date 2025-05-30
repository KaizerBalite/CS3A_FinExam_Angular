document.addEventListener('DOMContentLoaded', () => {

  // login form
  const loginForm = document.getElementById('ppLoginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const username = document.getElementById('petEmail').value;
      const password = document.getElementById('petPass').value;

      if (username && password) {
        alert(`🐶 Welcome back, ${username}!`);
      } else {
        alert("Please fill out all fields, furry friend.");
      }
    });
  }

  // like button
  window.likePost = function (button) {
    const likeCountSpan = button.querySelector('.likeCount');
    let count = parseInt(likeCountSpan.textContent);
    count++;
    likeCountSpan.textContent = count;
  };

  // view post (modal)
  window.viewPost = function (button) {
  const postCard = button.closest('.postCard');
  const username = postCard.querySelector('.postHeader strong').textContent;
  const content = postCard.querySelector('p').textContent;
  const image = postCard.querySelector('img:not(.profilePic)');
  const profilePic = postCard.querySelector('.profilePic')?.src || 'assets/default.png';
  const date = postCard.querySelector('.postHeader span').textContent;

  document.getElementById('modalUser').textContent = username;
  document.getElementById('modalContent').textContent = content;
  document.getElementById('modalDate').textContent = "Posted on: " + date;
  document.getElementById('modalProfilePic').src = profilePic;

  const modalImage = document.getElementById('modalImage');
  if (image) {
    modalImage.src = image.src;
    modalImage.style.display = 'block';
  } else {
    modalImage.style.display = 'none';
  }

  document.getElementById('postModal').style.display = 'block';
};

  // close the modal
  const closeBtn = document.querySelector('.closeBtn');
  if (closeBtn) {
    closeBtn.onclick = function () {
      document.getElementById('postModal').style.display = 'none';
    };
  }

  window.onclick = function (event) {
    const modal = document.getElementById('postModal');
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  };
});