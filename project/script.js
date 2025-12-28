document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-confirm-delete').forEach(link => {
      link.addEventListener('click', function (e) {
        const msg = this.getAttribute('data-confirm') || 'Are you sure?';
        if (!confirm(msg)) {
          e.preventDefault();
        }
      });
    });
  
    document.querySelectorAll('.js-confirm-complete').forEach(link => {
      link.addEventListener('click', function (e) {
        const msg = this.getAttribute('data-confirm') || 'Mark as completed?';
        if (!confirm(msg)) {
          e.preventDefault();
        }
      });
    });
  
    const today = new Date();
    today.setHours(0,0,0,0);
  
    document.querySelectorAll('.task-card').forEach(card => {
      const due = card.getAttribute('data-due-date');
      const completed = card.getAttribute('data-completed') === '1';
  
      if (!completed && due) {
        const dueDate = new Date(due);
        dueDate.setHours(0,0,0,0);
        if (dueDate < today) {
          card.classList.add('overdue');
        }
      }
    });
  
   
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }
  
  document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
      loginForm.addEventListener('submit', (e) => {
        const email = document.getElementById('loginEmail').value.trim();
        const pass = document.getElementById('loginPassword').value;
  
        if (!isValidEmail(email)) {
          alert('Please enter a valid email address.');
          e.preventDefault();
          return;
        }
        if (pass.length < 6) {
          alert('Password must be at least 6 characters.');
          e.preventDefault();
          return;
        }
      });
    }
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
      registerForm.addEventListener('submit', (e) => {
        const email = document.getElementById('registerEmail').value.trim();
        const pass = document.getElementById('registerPassword').value;
        const confirm = document.getElementById('registerConfirmPassword')?.value ?? pass;
  
        if (!isValidEmail(email)) {
          alert('Please enter a valid email address.');
          e.preventDefault();
          return;
        }
        if (pass.length < 6) {
          alert('Password must be at least 6 characters.');
          e.preventDefault();
          return;
        }
        if (pass !== confirm) {
          alert('Passwords do not match.');
          e.preventDefault();
          return;
        }
      });
    }
  
    const addTaskForm = document.getElementById('addTaskForm');
    if (addTaskForm) {
      addTaskForm.addEventListener('submit', (e) => {
        const title = document.getElementById('taskTitle').value.trim();
        if (title.length < 2) {
          alert('Task title must be at least 2 characters.');
          e.preventDefault();
          return;
        }
      });
    }
  
    const editTaskForm = document.getElementById('editTaskForm');
    if (editTaskForm) {
      editTaskForm.addEventListener('submit', (e) => {
        const title = document.getElementById('editTaskTitle').value.trim();
        if (title.length < 2) {
          alert('Task title must be at least 2 characters.');
          e.preventDefault();
          return;
        }
      });
    }
  });

const themeToggle = document.getElementById('themeToggle');

if (localStorage.getItem('theme') === 'dark') {
  document.body.classList.add('dark');
  if (themeToggle) themeToggle.textContent = '☀ Light';
}

if (themeToggle) {
  themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark');

    if (document.body.classList.contains('dark')) {
      localStorage.setItem('theme', 'dark');
      themeToggle.textContent = '☀ Light';
    } else {
      localStorage.setItem('theme', 'light');
      themeToggle.textContent = '🌙 Dark';
    }
  });
};
});
