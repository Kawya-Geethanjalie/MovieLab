// MovieLab Authentication System
// Enhanced with profile image support and responsive design

class MovieLabAuth {
    constructor() {
        this.currentUser = null;
        this.isLoggedIn = false;
        this.init();
    }

    init() {
        this.checkUserSession();
        this.bindEvents();
    }

    // Check user session on page load
    async checkUserSession() {
        try {
            const response = await fetch('../library/checkSession.php');
            const data = await response.json();
            
            if (data.status === 'logged_in') {
                this.currentUser = data.user;
                this.isLoggedIn = true;
                this.updateNavbarForLoggedInUser();
            } else {
                this.isLoggedIn = false;
                this.updateNavbarForGuest();
            }
        } catch (error) {
            console.error('Session check error:', error);
            this.isLoggedIn = false;
            this.updateNavbarForGuest();
        }
    }

    // Update navbar for logged in user
    updateNavbarForLoggedInUser() {
        const signInBtn = document.getElementById('sign-in-btn');
        const mobileSignInBtn = document.getElementById('mobile-sign-in-btn');
        const userProfileSection = document.getElementById('user-profile-section');
        
        if (signInBtn) signInBtn.style.display = 'none';
        if (mobileSignInBtn) mobileSignInBtn.style.display = 'none';
        if (userProfileSection) {
            userProfileSection.style.display = 'flex';
            this.updateUserProfileDisplay();
        }
    }

    // Update navbar for guest user
    updateNavbarForGuest() {
        const signInBtn = document.getElementById('sign-in-btn');
        const mobileSignInBtn = document.getElementById('mobile-sign-in-btn');
        const userProfileSection = document.getElementById('user-profile-section');
        
        if (signInBtn) signInBtn.style.display = 'inline-flex';
        if (mobileSignInBtn) mobileSignInBtn.style.display = 'block';
        if (userProfileSection) userProfileSection.style.display = 'none';
    }

    // Update user profile display
    updateUserProfileDisplay() {
        const profileImg = document.getElementById('user-profile-img');
        const userName = document.getElementById('user-name-display');
        
        if (this.currentUser) {
            if (profileImg) {
                if (this.currentUser.profile_image) {
                    profileImg.src = '../uploads/profile_images/' + this.currentUser.profile_image;
                } else {
                    profileImg.src = 'https://via.placeholder.com/40x40/E50914/FFFFFF?text=' + this.currentUser.first_name.charAt(0);
                }
            }
            if (userName) {
                userName.textContent = this.currentUser.first_name;
            }
        }
    }

    // Show alert using SweetAlert2
    async showAlert(icon, title, text, confirmButtonText = 'OK') {
        return await Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: "#E50914",
            confirmButtonText: confirmButtonText,
            showClass: {
                popup: 'animate__animated animate__fadeInUp'
            }
        });
    }

    // Set loading state for buttons
    setLoadingState(button, isLoading) {
        if (isLoading) {
            button.classList.add('btn-loading');
            button.dataset.originalText = button.textContent;
            button.textContent = '';
        } else {
            button.classList.remove('btn-loading');
            button.textContent = button.dataset.originalText || 'Submit';
        }
    }

    // Open modal
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            const modalContent = modal.querySelector('.modal-enter');
            if (modalContent) {
                modalContent.classList.add('modal-enter');
            }
        }
    }

    // Close modal
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Clear validation messages
    clearValidationMessages() {
        document.querySelectorAll('.validation-message').forEach(msg => {
            msg.classList.remove('show', 'error', 'success');
        });
        document.querySelectorAll('.input-field').forEach(input => {
            input.classList.remove('error', 'success');
        });
    }

    // Preview uploaded image
    previewImage(input) {
        const preview = document.getElementById('image-preview');
        const file = input.files[0];
        
        if (file) {
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                this.showAlert('error', 'File Too Large', 'Please select an image smaller than 5MB');
                input.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                this.showAlert('error', 'Invalid File Type', 'Please select a JPEG, PNG, or GIF image');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.add('show');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.remove('show');
        }
    }

    // Toggle profile dropdown
    toggleProfileDropdown() {
        const dropdown = document.getElementById('profile-dropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    // Logout function
    async logout() {
        try {
            const response = await fetch('../library/logoutBackend.php', {
                method: 'POST'
            });
            const data = await response.json();
            
            if (data.status === 'success') {
                this.currentUser = null;
                this.isLoggedIn = false;
                this.updateNavbarForGuest();
                
                await this.showAlert('success', 'Logged Out', 'You have been successfully logged out!');
                window.location.reload();
            }
        } catch (error) {
            console.error('Logout error:', error);
            this.showAlert('error', 'Logout Error', 'Failed to logout. Please try again.');
        }
    }

    // Field validation
    validateField(input, fieldType) {
        const value = input.value.trim();
        const messageEl = document.getElementById(fieldType + '_message');
        let isValid = true;
        let message = '';

        // Clear previous styles
        input.classList.remove('error', 'success');
        if (messageEl) {
            messageEl.classList.remove('show', 'error', 'success');
        }

        switch(fieldType) {
            case 'first_name':
            case 'last_name':
                if (!value) {
                    message = 'This field is required';
                    isValid = false;
                } else if (!/^[a-zA-Z]{2,30}$/.test(value)) {
                    message = 'Name must contain only letters (2-30 characters)';
                    isValid = false;
                } else {
                    message = 'Looks good!';
                }
                break;

            case 'email':
                if (!value) {
                    message = 'Email is required';
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    message = 'Please enter a valid email address';
                    isValid = false;
                } else {
                    message = 'Valid email format!';
                }
                break;

            case 'username':
                if (!value) {
                    message = 'Username is required';
                    isValid = false;
                } else if (value.length < 5) {
                    message = 'Username must be at least 5 characters long';
                    isValid = false;
                } else {
                    message = 'Username looks good!';
                }
                break;

            case 'birthday':
                if (!value) {
                    message = 'Birthday is required';
                    isValid = false;
                } else {
                    const dob = new Date(value);
                    const now = new Date();
                    const age = now.getFullYear() - dob.getFullYear();
                    if (age < 13) {
                        message = 'You must be at least 13 years old';
                        isValid = false;
                    } else {
                        message = 'Age verified!';
                    }
                }
                break;

            case 'country':
                if (!value || value === 'Select Country') {
                    message = 'Please select your country';
                    isValid = false;
                } else {
                    message = 'Country selected!';
                }
                break;

            case 'password':
                if (!value) {
                    message = 'Password is required';
                    isValid = false;
                } else if (value.length < 8) {
                    message = 'Password must be at least 8 characters long';
                    isValid = false;
                } else {
                    message = 'Strong password!';
                }
                break;

            case 'confirm_password':
                const password = document.getElementById('password').value;
                if (!value) {
                    message = 'Please confirm your password';
                    isValid = false;
                } else if (value !== password) {
                    message = 'Passwords do not match';
                    isValid = false;
                } else {
                    message = 'Passwords match!';
                }
                break;
        }

        // Apply styles and show message
        if (value && messageEl) {
            input.classList.add(isValid ? 'success' : 'error');
            messageEl.textContent = message;
            messageEl.classList.add('show', isValid ? 'success' : 'error');
        }

        return isValid;
    }
}

// Initialize the authentication system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.movieLabAuth = new MovieLabAuth();
});

// Global functions for backward compatibility
function previewImage(input) {
    if (window.movieLabAuth) {
        window.movieLabAuth.previewImage(input);
    }
}

function validateField(input, fieldType) {
    if (window.movieLabAuth) {
        return window.movieLabAuth.validateField(input, fieldType);
    }
    return true;
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.type = field.type === "password" ? "text" : "password";
    }
}

function logout() {
    if (window.movieLabAuth) {
        window.movieLabAuth.logout();
    }
}

function toggleProfileDropdown() {
    if (window.movieLabAuth) {
        window.movieLabAuth.toggleProfileDropdown();
    }
}