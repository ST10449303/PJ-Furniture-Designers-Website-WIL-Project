/* ==========================================
   1. AUTH MODAL LOGIC
   ========================================== */
const authModal = document.getElementById('authModal');
const loginBtn = document.getElementById('loginBtn'); 
const closeBtn = document.getElementById('closeModal');
const openAuthBtns = document.querySelectorAll('.openAuth');

function toggleModal() {
    if (authModal) {
        const isFlex = authModal.style.display === 'flex';
        authModal.style.display = isFlex ? 'none' : 'flex';
    }
}

if (loginBtn) loginBtn.addEventListener('click', toggleModal);
if (closeBtn) closeBtn.addEventListener('click', toggleModal);

openAuthBtns.forEach(btn => {
    btn.onclick = function() {
        toggleModal();
        if (sidebar) sidebar.style.width = "0"; 
    };
});

window.onclick = function(event) {
    if (event.target == authModal) {
        authModal.style.display = "none";
    }
};

/* ==========================================
   2. SIDEBAR LOGIC
   ========================================== */
const sidebar = document.getElementById("sidebar");
const openBtn = document.getElementById("openSidebar"); // Ensure your menu icon has this ID
const closeBtnSidebar = document.getElementById("closeSidebar");

function openNav() {
    if (sidebar) sidebar.style.width = "280px";
}

function closeNav() {
    if (sidebar) sidebar.style.width = "0";
}

// Attach to standard buttons if they exist
if (openBtn) openBtn.onclick = openNav;
if (closeBtnSidebar) closeBtnSidebar.onclick = closeNav;

/* ==========================================
   3. SEARCH & FILTERING LOGIC
   ========================================== */
const searchForm = document.querySelector('.search-container form');
const searchInput = document.querySelector('.search-container input');
const collectionCards = document.querySelectorAll('.collection-card, .item-card');
const categoryLinks = document.querySelectorAll('.cat-link');

// Functional Search Logic
if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Stay on page to filter
        const searchTerm = searchInput.value.toLowerCase().trim();

        collectionCards.forEach(card => {
            const title = card.querySelector('h2, h3').textContent.toLowerCase();
            if (title.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Scroll to results
        const firstMatch = [...collectionCards].find(c => c.style.display !== 'none');
        if(firstMatch) firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
}

// Category Filtering
categoryLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        const selectedCategory = this.textContent.trim().toLowerCase();
        
        collectionCards.forEach(card => {
            const cardTitle = card.querySelector('h2, h3').textContent.toLowerCase();
            if (selectedCategory === 'all' || cardTitle.includes(selectedCategory)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

/* ==========================================
   4. ADD TO CART LOGIC
   ========================================== */
let cart = JSON.parse(localStorage.getItem('pj_cart')) || [];

function updateCartUI() {
    const cartCounts = document.querySelectorAll('.cart-count');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    cartCounts.forEach(el => {
        el.textContent = totalItems;
        el.style.display = totalItems > 0 ? 'block' : 'none';
    });
    
    localStorage.setItem('pj_cart', JSON.stringify(cart));
}

// Attach to all "Add to Cart" buttons
const addToCartBtns = document.querySelectorAll('.add-cart-btn');

addToCartBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const card = this.closest('.item-card');
        const product = {
            id: this.dataset.id || Math.random(), // Unique ID
            name: card.querySelector('h3').textContent,
            price: card.querySelector('.price').textContent,
            image: card.querySelector('img').src,
            quantity: 1
        };

        const existingItem = cart.find(item => item.name === product.name);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push(product);
        }

        // Visual feedback
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-check"></i> Added!';
        this.style.background = "#25D366";
        
        setTimeout(() => {
            this.innerHTML = originalText;
            this.style.background = "";
        }, 1500);

        updateCartUI();
    });
});

/* ==========================================
   5. UTILITIES & INITIALIZATION
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    updateCartUI(); // Load cart count on page load

    const printBtn = document.getElementById('printReceiptBtn');
    if (printBtn) {
        printBtn.addEventListener('click', () => window.print());
    }
});

// Auto-close sidebar on link click
const sidebarLinks = document.querySelectorAll('.sidebar-content a');
sidebarLinks.forEach(link => {
    link.addEventListener('click', closeNav);
});