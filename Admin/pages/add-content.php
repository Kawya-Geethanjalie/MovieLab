<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieLab - Add Content</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... your existing CSS remains the same ... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-red: #d32f2f;
            --dark-red: #b71c1c;
            --light-red: #ff6659;
            --black: #121212;
            --dark-gray: #1e1e1e;
            --medium-gray: #2d2d2d;
            --light-gray: #424242;
            --text-light: #f5f5f5;
            --text-gray: #b0b0b0;
        }

        body {
            background-color: var(--black);
            color: var(--text-light);
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: linear-gradient(to right, var(--black), var(--dark-red));
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid var(--primary-red);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(to right, var(--text-light), var(--light-red));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .header p {
            color: var(--text-gray);
            font-size: 1.1rem;
        }

        .content-tabs {
            display: flex;
            background-color: var(--dark-gray);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .tab-btn {
            flex: 1;
            padding: 20px;
            background: none;
            border: none;
            color: var(--text-gray);
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .tab-btn:hover {
            background-color: var(--medium-gray);
            color: var(--text-light);
        }

        .tab-btn.active {
            background-color: var(--primary-red);
            color: white;
        }

        .tab-content {
            display: none;
            background-color: var(--dark-gray);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-title {
            color: var(--primary-red);
            margin-bottom: 25px;
            font-size: 1.8rem;
            border-bottom: 2px solid var(--light-gray);
            padding-bottom: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-light);
            font-weight: 500;
        }

        .form-group label.required::after {
            content: ' *';
            color: var(--primary-red);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            background-color: var(--light-gray);
            border: 2px solid transparent;
            border-radius: 6px;
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-red);
            background-color: var(--medium-gray);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 20px;
            background-color: var(--medium-gray);
            border: 2px dashed var(--light-gray);
            border-radius: 6px;
            color: var(--text-gray);
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-input-label:hover {
            border-color: var(--primary-red);
            color: var(--text-light);
        }

        .file-input-label.has-file {
            border-color: var(--primary-red);
            color: var(--primary-red);
            background-color: rgba(211, 47, 47, 0.1);
        }

        .file-name {
            margin-top: 5px;
            font-size: 0.9rem;
            color: var(--text-gray);
            font-style: italic;
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid var(--light-gray);
        }

        .btn {
            padding: 14px 35px;
            background-color: var(--primary-red);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 180px;
            justify-content: center;
        }

        .btn:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(211, 47, 47, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background-color: var(--light-gray);
        }

        .btn-secondary:hover {
            background-color: var(--medium-gray);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 18px 25px;
            background-color: var(--primary-red);
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            max-width: 400px;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast.error {
            background-color: #f44336;
        }

        .toast.success {
            background-color: #4caf50;
        }

        .toast.info {
            background-color: #2196f3;
        }

        .toast i {
            font-size: 1.3rem;
        }

        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* File preview */
        .file-preview {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            background-color: var(--medium-gray);
            border-radius: 6px;
            display: none;
        }

        .file-preview.show {
            display: flex;
        }

        .file-preview-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }

        .file-preview-info {
            flex: 1;
        }

        .file-preview-name {
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 5px;
        }

        .file-preview-size {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header {
                padding: 20px;
                text-align: center;
            }

            .header h1 {
                font-size: 2rem;
            }

            .tab-btn {
                padding: 15px 10px;
                font-size: 1rem;
            }

            .tab-content {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .toast {
                left: 20px;
                right: 20px;
                max-width: none;
            }
        }

        /* Helper text */
        .helper-text {
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-top: 5px;
            font-style: italic;
        }

        /* Back link */
        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--light-red);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-plus-circle"></i> Add New Content</h1>
            <p>Add new movies or songs to the MovieLab database</p>
        </div>

        <!-- Content Type Tabs -->
        <div class="content-tabs">
            <button class="tab-btn active" id="movie-tab" type="button">
                <i class="fas fa-film"></i> Add Movie
            </button>
            <button class="tab-btn" id="song-tab" type="button">
                <i class="fas fa-music"></i> Add Song
            </button>
        </div>

        <!-- Movie Form -->
        <div class="tab-content active" id="movie-form">
            <div class="form-container">
                <h2 class="form-title"><i class="fas fa-video"></i> Movie Details</h2>
                <form id="movieForm" enctype="multipart/form-data">
                    <input type="hidden" name="content_type" value="movie">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="movie_title" class="required">Movie Title</label>
                            <input type="text" id="movie_title" name="title" required 
                                   placeholder="Enter movie title">
                        </div>

                        <div class="form-group">
                            <label for="release_year" class="required">Release Year</label>
                            <input type="number" id="release_year" name="release_year" 
                                   min="1900" max="2030" required 
                                   placeholder="2023">
                            <div class="helper-text">Enter year between 1900-2030</div>
                        </div>

                        <div class="form-group">
                            <label for="movie_genre" class="required">Genre</label>
                            <select id="movie_genre" name="genre" required>
                                <option value="">Select Genre</option>
                                <option value="Action">Action</option>
                                <option value="Adventure">Adventure</option>
                                <option value="Animation">Animation</option>
                                <option value="Biography">Biography</option>
                                <option value="Comedy">Comedy</option>
                                <option value="Crime">Crime</option>
                                <option value="Drama">Drama</option>
                                <option value="Family">Family</option>
                                <option value="Fantasy">Fantasy</option>
                                <option value="History">History</option>
                                <option value="Horror">Horror</option>
                                <option value="Musical">Musical</option>
                                <option value="Mystery">Mystery</option>
                                <option value="Romance">Romance</option>
                                <option value="Sci-Fi">Sci-Fi</option>
                                <option value="Thriller">Thriller</option>
                                <option value="War">War</option>
                                <option value="Western">Western</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="rating">Rating (0-10)</label>
                            <input type="number" id="rating" name="rating" 
                                   min="0" max="10" step="0.1"
                                   placeholder="7.5">
                            <div class="helper-text">Optional: Enter rating from 0-10</div>
                        </div>

                        <div class="form-group">
                            <label for="duration" class="required">Duration (minutes)</label>
                            <input type="number" id="duration" name="duration" 
                                   min="1" max="500" required 
                                   placeholder="120">
                            <div class="helper-text">Enter duration in minutes (1-500)</div>
                        </div>

                        <div class="form-group">
                            <label for="trailer_url">Trailer URL</label>
                            <input type="url" id="trailer_url" name="trailer_url" 
                                   placeholder="https://youtube.com/watch?v=...">
                        </div>

                        <div class="form-group full-width">
                            <label for="poster_image">Poster Image</label>
                            <div class="file-input-wrapper">
                                <input type="file" id="poster_image" name="poster_image" 
                                       accept=".jpg,.jpeg,.png,.gif,.webp">
                                <div class="file-input-label" id="poster_image_label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose poster image (max 5MB, 1024x1024px)</span>
                                </div>
                            </div>
                            <div class="file-preview" id="poster_preview">
                                <img src="" alt="Preview" class="file-preview-image">
                                <div class="file-preview-info">
                                    <div class="file-preview-name" id="poster_name"></div>
                                    <div class="file-preview-size" id="poster_size"></div>
                                </div>
                            </div>
                            <div class="helper-text">Recommended: JPG, PNG, GIF, WEBP (max 1024x1024px)</div>
                        </div>

                        <div class="form-group full-width">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" 
                                      placeholder="Enter movie description..."></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="movieResetBtn">
                            <i class="fas fa-redo"></i> Reset Form
                        </button>
                        <button type="submit" class="btn" id="movieSubmitBtn">
                            <i class="fas fa-plus"></i> Add Movie
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Song Form -->
        <div class="tab-content" id="song-form">
            <div class="form-container">
                <h2 class="form-title"><i class="fas fa-headphones"></i> Song Details</h2>
                <form id="songForm" enctype="multipart/form-data">
                    <input type="hidden" name="content_type" value="song">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="song_title" class="required">Song Title</label>
                            <input type="text" id="song_title" name="title" required 
                                   placeholder="Enter song title">
                        </div>

                        <div class="form-group">
                            <label for="artist" class="required">Artist</label>
                            <input type="text" id="artist" name="artist" required 
                                   placeholder="Enter artist name">
                        </div>

                        <div class="form-group">
                            <label for="album">Album</label>
                            <input type="text" id="album" name="album" 
                                   placeholder="Enter album name">
                        </div>

                        <div class="form-group">
                            <label for="song_genre" class="required">Genre</label>
                            <select id="song_genre" name="genre" required>
                                <option value="">Select Genre</option>
                                <option value="Pop">Pop</option>
                                <option value="Rock">Rock</option>
                                <option value="Hip Hop">Hip Hop</option>
                                <option value="R&B">R&B</option>
                                <option value="Country">Country</option>
                                <option value="Jazz">Jazz</option>
                                <option value="Classical">Classical</option>
                                <option value="Electronic">Electronic</option>
                                <option value="Reggae">Reggae</option>
                                <option value="Blues">Blues</option>
                                <option value="Metal">Metal</option>
                                <option value="Folk">Folk</option>
                                <option value="Soul">Soul</option>
                                <option value="Funk">Funk</option>
                                <option value="Reggaeton">Reggaeton</option>
                                <option value="Latin">Latin</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="song_duration" class="required">Duration (seconds)</label>
                            <input type="number" id="song_duration" name="duration" 
                                   min="1" max="1800" required 
                                   placeholder="240">
                            <div class="helper-text">Enter duration in seconds (1-1800)</div>
                        </div>

                        <div class="form-group">
                            <label for="language">Language</label>
                            <input type="text" id="language" name="language" 
                                   placeholder="English">
                        </div>

                        <div class="form-group full-width">
                            <label for="cover_image">Cover Image</label>
                            <div class="file-input-wrapper">
                                <input type="file" id="cover_image" name="cover_image" 
                                       accept=".jpg,.jpeg,.png,.gif,.webp">
                                <div class="file-input-label" id="cover_image_label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose cover image (max 5MB, 1024x1024px)</span>
                                </div>
                            </div>
                            <div class="file-preview" id="cover_preview">
                                <img src="" alt="Preview" class="file-preview-image">
                                <div class="file-preview-info">
                                    <div class="file-preview-name" id="cover_name"></div>
                                    <div class="file-preview-size" id="cover_size"></div>
                                </div>
                            </div>
                            <div class="helper-text">Optional: JPG, PNG, GIF, WEBP (max 1024x1024px)</div>
                        </div>

                        <div class="form-group full-width">
                            <label for="audio_file">Audio File</label>
                            <div class="file-input-wrapper">
                                <input type="file" id="audio_file" name="audio_file" 
                                       accept=".mp3,.wav,.ogg,.m4a,.aac,.flac,.wma">
                                <div class="file-input-label" id="audio_file_label">
                                    <i class="fas fa-file-audio"></i>
                                    <span>Choose audio file (max 20MB)</span>
                                </div>
                            </div>
                            <div class="file-preview" id="audio_preview">
                                <div class="file-preview-info">
                                    <div class="file-preview-name" id="audio_name"></div>
                                    <div class="file-preview-size" id="audio_size"></div>
                                </div>
                            </div>
                            <div class="helper-text">Optional: MP3, WAV, OGG, M4A, AAC, FLAC, WMA (max 20MB)</div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="songResetBtn">
                            <i class="fas fa-redo"></i> Reset Form
                        </button>
                        <button type="submit" class="btn" id="songSubmitBtn">
                            <i class="fas fa-plus"></i> Add Song
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Back to Management Link -->
        <a href="content_management.html" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Content Management
        </a>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-info-circle"></i>
        <span id="toast-message">Message will appear here</span>
    </div>

    <script>
        // DOM Elements
        const movieTab = document.getElementById('movie-tab');
        const songTab = document.getElementById('song-tab');
        const movieForm = document.getElementById('movie-form');
        const songFormDiv = document.getElementById('song-form');
        const movieFormElement = document.getElementById('movieForm');
        const songFormElement = document.getElementById('songForm');
        const movieSubmitBtn = document.getElementById('movieSubmitBtn');
        const songSubmitBtn = document.getElementById('songSubmitBtn');
        const movieResetBtn = document.getElementById('movieResetBtn');
        const songResetBtn = document.getElementById('songResetBtn');
        
        // Toast elements
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        // File input elements
        const posterImageInput = document.getElementById('poster_image');
        const posterImageLabel = document.getElementById('poster_image_label');
        const posterPreview = document.getElementById('poster_preview');
        const posterName = document.getElementById('poster_name');
        const posterSize = document.getElementById('poster_size');
        const posterPreviewImage = posterPreview.querySelector('.file-preview-image');
        
        const coverImageInput = document.getElementById('cover_image');
        const coverImageLabel = document.getElementById('cover_image_label');
        const coverPreview = document.getElementById('cover_preview');
        const coverName = document.getElementById('cover_name');
        const coverSize = document.getElementById('cover_size');
        const coverPreviewImage = coverPreview.querySelector('.file-preview-image');
        
        const audioFileInput = document.getElementById('audio_file');
        const audioFileLabel = document.getElementById('audio_file_label');
        const audioPreview = document.getElementById('audio_preview');
        const audioName = document.getElementById('audio_name');
        const audioSize = document.getElementById('audio_size');

        // Tab switching
        movieTab.addEventListener('click', () => {
            movieTab.classList.add('active');
            songTab.classList.remove('active');
            movieForm.classList.add('active');
            songFormDiv.classList.remove('active');
        });

        songTab.addEventListener('click', () => {
            songTab.classList.add('active');
            movieTab.classList.remove('active');
            songFormDiv.classList.add('active');
            movieForm.classList.remove('active');
        });

        // File input handling for movie poster
        posterImageInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                // Validate file size
                if (file.size > 5 * 1024 * 1024) {
                    showToast('File is too large. Maximum size is 5MB.', 'error');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showToast('Invalid file type. Please select JPG, PNG, GIF, or WEBP image.', 'error');
                    this.value = '';
                    return;
                }
                
                // Update label
                posterImageLabel.classList.add('has-file');
                posterImageLabel.innerHTML = `<i class="fas fa-check-circle"></i> <span>${file.name}</span>`;
                
                // Show preview
                posterPreview.classList.add('show');
                posterName.textContent = file.name;
                posterSize.textContent = formatFileSize(file.size);
                
                // Create image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    posterPreviewImage.src = e.target.result;
                };
                reader.onloadend = function() {
                    // Validate image dimensions after loading
                    const img = new Image();
                    img.onload = function() {
                        if (this.width > 1024 || this.height > 1024) {
                            showToast(`Image dimensions (${this.width}x${this.height}) exceed 1024x1024px limit`, 'error');
                            posterImageInput.value = '';
                            posterImageLabel.classList.remove('has-file');
                            posterPreview.classList.remove('show');
                        }
                    };
                    img.src = reader.result;
                };
                reader.readAsDataURL(file);
            } else {
                // Reset if no file
                resetFileInput(posterImageLabel, posterPreview, 'Choose poster image (max 5MB, 1024x1024px)', 'cloud-upload-alt');
            }
        });

        // File input handling for song cover
        coverImageInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                // Validate file size
                if (file.size > 5 * 1024 * 1024) {
                    showToast('File is too large. Maximum size is 5MB.', 'error');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showToast('Invalid file type. Please select JPG, PNG, GIF, or WEBP image.', 'error');
                    this.value = '';
                    return;
                }
                
                // Update label
                coverImageLabel.classList.add('has-file');
                coverImageLabel.innerHTML = `<i class="fas fa-check-circle"></i> <span>${file.name}</span>`;
                
                // Show preview
                coverPreview.classList.add('show');
                coverName.textContent = file.name;
                coverSize.textContent = formatFileSize(file.size);
                
                // Create image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    coverPreviewImage.src = e.target.result;
                };
                reader.onloadend = function() {
                    // Validate image dimensions after loading
                    const img = new Image();
                    img.onload = function() {
                        if (this.width > 1024 || this.height > 1024) {
                            showToast(`Image dimensions (${this.width}x${this.height}) exceed 1024x1024px limit`, 'error');
                            coverImageInput.value = '';
                            coverImageLabel.classList.remove('has-file');
                            coverPreview.classList.remove('show');
                        }
                    };
                    img.src = reader.result;
                };
                reader.readAsDataURL(file);
            } else {
                // Reset if no file
                resetFileInput(coverImageLabel, coverPreview, 'Choose cover image (max 5MB, 1024x1024px)', 'cloud-upload-alt');
            }
        });

        // File input handling for audio file
        audioFileInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                // Validate file size
                if (file.size > 20 * 1024 * 1024) {
                    showToast('File is too large. Maximum size is 20MB.', 'error');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/x-m4a', 'audio/aac', 'audio/flac', 'audio/x-ms-wma'];
                if (!validTypes.includes(file.type)) {
                    showToast('Invalid file type. Please select MP3, WAV, OGG, M4A, AAC, FLAC, or WMA audio file.', 'error');
                    this.value = '';
                    return;
                }
                
                // Update label
                audioFileLabel.classList.add('has-file');
                audioFileLabel.innerHTML = `<i class="fas fa-check-circle"></i> <span>${file.name}</span>`;
                
                // Show preview
                audioPreview.classList.add('show');
                audioName.textContent = file.name;
                audioSize.textContent = formatFileSize(file.size);
            } else {
                // Reset if no file
                resetFileInput(audioFileLabel, audioPreview, 'Choose audio file (max 20MB)', 'file-audio');
            }
        });

        // Helper function to reset file inputs
        function resetFileInput(labelElement, previewElement, defaultText, iconClass) {
            labelElement.classList.remove('has-file');
            labelElement.innerHTML = `<i class="fas fa-${iconClass}"></i> <span>${defaultText}</span>`;
            previewElement.classList.remove('show');
        }

        // Reset buttons
        movieResetBtn.addEventListener('click', () => {
            movieFormElement.reset();
            resetFileInput(posterImageLabel, posterPreview, 'Choose poster image (max 5MB, 1024x1024px)', 'cloud-upload-alt');
            showToast('Movie form reset', 'info');
        });

        songResetBtn.addEventListener('click', () => {
            songFormElement.reset();
            resetFileInput(coverImageLabel, coverPreview, 'Choose cover image (max 5MB, 1024x1024px)', 'cloud-upload-alt');
            resetFileInput(audioFileLabel, audioPreview, 'Choose audio file (max 20MB)', 'file-audio');
            showToast('Song form reset', 'info');
        });

        // Form submission - Movie
        movieFormElement.addEventListener('submit', async (e) => {
            e.preventDefault();
            await submitForm(movieFormElement, movieSubmitBtn, 'movie');
        });

        // Form submission - Song
        songFormElement.addEventListener('submit', async (e) => {
            e.preventDefault();
            await submitForm(songFormElement, songSubmitBtn, 'song');
        });

        // Submit form function - FIXED VERSION
        async function submitForm(formElement, submitBtn, type) {
            console.log(`Submitting ${type} form...`);
            
            // Validate form
            if (!formElement.checkValidity()) {
                showToast('Please fill in all required fields correctly', 'error');
                // Trigger HTML5 validation
                formElement.reportValidity();
                return;
            }

            // Check file sizes
            let isValid = true;
            let errorMessage = '';
            
            if (type === 'movie') {
                const posterFile = posterImageInput.files[0];
                if (posterFile && posterFile.size > 5 * 1024 * 1024) {
                    isValid = false;
                    errorMessage = 'Poster image must be 5MB or smaller';
                }
            } else if (type === 'song') {
                const coverFile = coverImageInput.files[0];
                const audioFile = audioFileInput.files[0];
                
                if (coverFile && coverFile.size > 5 * 1024 * 1024) {
                    isValid = false;
                    errorMessage = 'Cover image must be 5MB or smaller';
                }
                
                if (audioFile && audioFile.size > 20 * 1024 * 1024) {
                    isValid = false;
                    errorMessage = 'Audio file must be 20MB or smaller';
                }
                
                // Audio file is required for songs
                if (!audioFile) {
                    isValid = false;
                    errorMessage = 'Audio file is required for songs';
                }
            }

            if (!isValid) {
                showToast(errorMessage, 'error');
                return;
            }

            // Create FormData object
            const formData = new FormData(formElement);
            
            // Log FormData contents for debugging
            console.log('FormData contents:');
            for (let [key, value] of formData.entries()) {
                if (value instanceof File) {
                    console.log(`${key}: ${value.name} (${value.size} bytes, ${value.type})`);
                } else {
                    console.log(`${key}: ${value}`);
                }
            }
            
            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<div class="spinner"></div> Adding...';
            submitBtn.disabled = true;

            try {
                console.log('Sending request to server...');
                
                // Send request to backend - CORRECTED ENDPOINT
                // Note: Your backend file is add-content.php, not add_content.php
                const response = await fetch('add-content.php', {
                    method: 'POST',
                    body: formData,
                    // Do NOT set Content-Type header when sending FormData
                    // Let the browser set it automatically with boundary
                });

                console.log('Response received:', response.status, response.statusText);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const textResponse = await response.text();
                console.log('Raw response:', textResponse);
                
                let result;
                try {
                    result = JSON.parse(textResponse);
                } catch (parseError) {
                    console.error('Failed to parse JSON:', parseError);
                    throw new Error('Invalid response from server');
                }

                console.log('Parsed response:', result);

                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                if (result.success) {
                    showToast(result.message, 'success');
                    // Reset form after successful submission
                    setTimeout(() => {
                        formElement.reset();
                        if (type === 'movie') {
                            movieResetBtn.click();
                        } else {
                            songResetBtn.click();
                        }
                    }, 1500);
                } else {
                    showToast(result.message || 'Failed to add content', 'error');
                }
            } catch (error) {
                console.error('Error during submission:', error);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                let errorMsg = 'Network error. Please try again.';
                if (error.message.includes('HTTP error')) {
                    errorMsg = `Server error: ${error.message}`;
                } else if (error.message.includes('Invalid response')) {
                    errorMsg = 'Server returned an invalid response';
                }
                
                showToast(errorMsg, 'error');
            }
        }

        // Utility function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Toast notification function
        function showToast(message, type = 'info') {
            // Set message and type
            toastMessage.textContent = message;
            toast.className = 'toast';
            toast.classList.add(type);
            
            // Set icon based on type
            const icon = toast.querySelector('i');
            if (type === 'success') {
                icon.className = 'fas fa-check-circle';
            } else if (type === 'error') {
                icon.className = 'fas fa-exclamation-circle';
            } else {
                icon.className = 'fas fa-info-circle';
            }
            
            // Show toast
            toast.classList.add('show');
            
            // Hide toast after 5 seconds
            setTimeout(() => {
                toast.classList.remove('show');
            }, 5000);
        }

        // Initialize form validation
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Add content page loaded');
            
            // Add required attribute validation
            const requiredInputs = document.querySelectorAll('input[required], select[required]');
            requiredInputs.forEach(input => {
                input.addEventListener('invalid', function(e) {
                    e.preventDefault();
                    const label = this.labels[0];
                    const fieldName = label ? label.textContent.replace(' *', '').trim() : 'this field';
                    showToast(`Please fill in the "${fieldName}" field`, 'error');
                });
            });
            
            // Clean trailer URL input
            const trailerUrlInput = document.getElementById('trailer_url');
            if (trailerUrlInput) {
                trailerUrlInput.addEventListener('blur', function() {
                    let url = this.value.trim();
                    if (url && url.endsWith('&l')) {
                        url = url.replace(/&l$/, '');
                        this.value = url;
                        showToast('Fixed trailer URL format', 'info');
                    }
                });
            }
        });
    </script>
</body>
</html>