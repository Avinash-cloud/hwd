import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global helper functions for Alpine components
window.productCard = function(productId) {
    return {
        adding: false,
        addToCart() {
            this.adding = true;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(res => {
                if (res.status === 401 || res.status === 403) {
                    return res.json().then(data => {
                        window.location.href = data.redirect || '/login';
                    });
                }
                return res.json();
            })
            .then(data => {
                this.adding = false;
                if (data && data.success) {
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    window.dispatchEvent(new CustomEvent('open-cart-drawer'));
                }
            })
            .catch(() => {
                this.adding = false;
            });
        }
    };
};

window.navigationComponent = function() {
    return {
        mobileMenuOpen: false,
        searchQuery: '',
        searchResults: [],
        searchOpen: false,

        performSearch() {
            if (this.searchQuery.trim().length < 2) {
                this.searchResults = [];
                this.searchOpen = false;
                return;
            }

            fetch('/api/search?q=' + encodeURIComponent(this.searchQuery), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.searchResults = data || [];
                this.searchOpen = this.searchResults.length > 0;
            })
            .catch(() => {
                this.searchResults = [];
            });
        }
    };
};

window.cartDrawer = function() {
    return {
        isOpen: false,
        loading: false,
        items: [],
        subtotal: 0,
        shipping: 0,
        total: 0,
        itemsCount: 0,

        init() {
            this.fetchCart();
        },

        openDrawer() {
            this.isOpen = true;
            this.fetchCart();
        },

        closeDrawer() {
            this.isOpen = false;
        },

        itemsCountText() {
            if (this.itemsCount === 0) return '0 items';
            return this.itemsCount + (this.itemsCount === 1 ? ' sacred item' : ' sacred items');
        },

        fetchCart() {
            fetch('/cart/api/details', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.cart) {
                    this.applyCartData(data.cart);
                }
            })
            .catch(() => {});
        },

        applyCartData(cart) {
            this.items = cart.items || [];
            this.subtotal = parseFloat(cart.subtotal) || 0;
            this.shipping = parseFloat(cart.shipping_amount) || 0;
            this.total = parseFloat(cart.total_amount) || 0;
            this.itemsCount = parseInt(cart.total_items_count) || this.items.reduce((acc, it) => acc + (it.quantity || 1), 0);

            const badges = document.querySelectorAll('.cart-count-badge');
            badges.forEach(b => {
                b.textContent = this.itemsCount;
                if (this.itemsCount > 0) {
                    b.classList.remove('hidden');
                } else {
                    b.classList.add('hidden');
                }
            });
        },

        updateQuantity(itemId, quantity) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch('/cart/update/' + itemId, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ quantity })
            })
            .then(res => res.json())
            .then(data => {
                if (data.cart) {
                    this.applyCartData(data.cart);
                } else {
                    this.fetchCart();
                }
            })
            .catch(() => {});
        },

        removeItem(itemId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch('/cart/remove/' + itemId, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.cart) {
                    this.applyCartData(data.cart);
                } else {
                    this.fetchCart();
                }
            })
            .catch(() => {});
        }
    };
};

window.pdpManager = function(productId, initialImage) {
    return {
        currentImage: initialImage,
        selectedThumb: 0,
        quantity: 1,
        adding: false,

        setMainImage(url, index) {
            this.currentImage = url;
            this.selectedThumb = index;
        },

        addToCart(redirectCheckout = false) {
            this.adding = true;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId, quantity: this.quantity })
            })
            .then(res => {
                if (res.status === 401 || res.status === 403) {
                    return res.json().then(data => {
                        window.location.href = data.redirect || '/login';
                    });
                }
                return res.json();
            })
            .then(data => {
                this.adding = false;
                if (data && data.success) {
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    if (redirectCheckout) {
                        window.location.href = '/checkout';
                    } else {
                        window.dispatchEvent(new CustomEvent('open-cart-drawer'));
                    }
                }
            })
            .catch(() => {
                this.adding = false;
            });
        }
    };
};

Alpine.start();
