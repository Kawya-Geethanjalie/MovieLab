<?php 
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include("../include/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management - Movie Lab Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        :root { --primary-red: #E50914; --dark-bg: #0c0c0c; --card-bg: #1a1a1a; --modal-bg: #121212; --text-gray: #888; --success: #10B981; --input-bg: #2a2a2a; }
        body { min-height: 100vh; background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1a1a 100%); color: #fff; overflow-x: hidden; }
        .content-management { padding: 20px; max-width: 1400px; margin: 0 auto; width: 100%; }
        .page-header { display: flex; flex-direction: column; gap: 20px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid rgba(229, 9, 20, 0.3); }
        .header-top { display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 28px; font-weight: 700; background: linear-gradient(90deg, #fff 0%, var(--primary-red) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .filter-bar { display: flex; flex-wrap: wrap; gap: 15px; background: rgba(255, 255, 255, 0.03); padding: 15px; border-radius: 10px; align-items: center; }
        .search-box { flex: 1; min-width: 250px; position: relative; }
        .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-gray); }
        .search-box input { width: 100%; padding: 10px 10px 10px 35px; background: var(--input-bg); border: 1px solid #333; border-radius: 6px; color: #fff; }
        .filter-select { padding: 10px; background: var(--input-bg); border: 1px solid #333; border-radius: 6px; color: #fff; min-width: 150px; }
        .content-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
        .content-card { background: var(--card-bg); border-radius: 12px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.05); transition: transform 0.3s ease; }
        .content-card:hover { transform: translateY(-5px); border-color: rgba(229, 9, 20, 0.3); }
        .content-poster { height: 200px; position: relative; background: #000; display: flex; align-items: center; justify-content: center; text-align: center; overflow: hidden; }
        .content-poster img { width: 100%; height: 100%; object-fit: cover; }
        .no-image-text { padding: 20px; color: #444; font-weight: bold; font-size: 14px; text-transform: uppercase; }
        .content-info { padding: 18px; }
        .content-title { font-size: 18px; margin-bottom: 8px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .content-meta { display: flex; flex-wrap: wrap; gap: 10px; font-size: 12px; color: var(--text-gray); margin-bottom: 15px; }
        .btn { padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; color: white; }
        .btn-primary { background: var(--primary-red); }
        .btn-sm { padding: 8px 14px; font-size: 13px; flex: 1; justify-content: center; }
        
        /* MODAL STYLES */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.9); backdrop-filter: blur(8px); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s ease; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-container { background: var(--modal-bg); width: 95%; max-width: 650px; max-height: 90vh; border-radius: 15px; position: relative; border: 2px solid var(--primary-red); box-shadow: 0 0 30px rgba(229, 9, 20, 0.5); transform: translateY(20px); transition: transform 0.3s ease; overflow-y: auto; }
        .modal-overlay.active .modal-container { transform: translateY(0); }
        .close-modal { position: absolute; top: 10px; right: 10px; background: transparent; border: none; color: #fff; font-size: 45px; line-height: 1; cursor: pointer; z-index: 101; padding: 5px 15px; transition: all 0.2s ease; }
        .close-modal:hover { color: var(--primary-red); background: rgba(229, 9, 20, 0.1); text-shadow: 0 0 10px var(--primary-red); }
        .modal-banner { width: 100%; height: 220px; background: #000; display: flex; align-items: center; justify-content: center; }
        .modal-banner img { width: 100%; height: 100%; object-fit: contain; }
        .modal-body { padding: 25px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 11px; color: var(--text-gray); margin-bottom: 5px; text-transform: uppercase; font-weight: 700; }
        .form-control { width: 100%; padding: 10px; background: var(--input-bg); border: 1px solid #333; border-radius: 5px; color: #fff; font-size: 14px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .movie-only, .song-only { display: none; }
        .is-movie .movie-only { display: block; }
        .is-song .song-only { display: block; }
        .alert { position: fixed; top: 20px; right: 20px; z-index: 2000; padding: 15px 25px; border-radius: 8px; color: #fff; background: var(--success); box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: 0.3s; }
        
        /* Audio Player Styling */
        #audioPreviewContainer { background: rgba(255,255,255,0.05); padding: 10px; border-radius: 5px; margin-bottom: 10px; border: 1px solid #333; }
        audio::-webkit-media-controls-panel { background-color: #f0f0f0; }
    </style>
</head>
<body>

    <div class="content-management">
        <div class="page-header">
            <div class="header-top">
                <h1 class="page-title">Content Management</h1>
                <a href="add-content.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Content</a>
            </div>
            
            <div class="filter-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by Title, Artist or Genre..." oninput="applyFilters()">
                </div>
                
                <select id="typeFilter" class="filter-select" onchange="handleTypeChange()">
                    <option value="all">All Types</option>
                    <option value="movie">Movies</option>
                    <option value="song">Songs</option>
                </select>

                <select id="genreFilter" class="filter-select" onchange="applyFilters()">
                    <option value="all">All Genres</option>
                </select>

                <select id="sortFilter" class="filter-select" onchange="applyFilters()">
                    <option value="newest">Recently Added</option>
                    <option value="oldest">Oldest First</option>
                    <option value="az">Alphabetical (A-Z)</option>
                </select>
            </div>
        </div>

        <div id="alertsContainer"></div>
        <div class="content-grid" id="contentGrid"></div>
    </div>

    <div class="modal-overlay" id="editModal">
        <div class="modal-container" id="modalFrame">
            <button class="close-modal" id="closeModal">&times;</button>
            
            <div class="modal-banner" id="bannerArea">
                <img id="posterPreview" src="" alt="Preview">
            </div>

            <div class="modal-body">
                <h3 id="modalHeadTitle" style="color: var(--primary-red); margin-bottom: 20px; text-transform: uppercase; font-size: 20px;">Edit Content</h3>
                
                <form id="updateForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editId">
                    <input type="hidden" name="type" id="editType">
                    <input type="hidden" name="csrf_token" id="csrfToken">

                    <div class="form-row">
                        <div class="form-group">
                            <label id="imageLabel">Image / Poster</label>
                            <input type="file" name="poster_image" id="editImage" class="form-control" accept="image/*" onchange="previewFile()">
                        </div>
                        
                        <div class="form-group song-only">
                            <label>Audio File (MP3)</label>
                            
                            <div id="audioPreviewContainer" style="display:none;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                    <span style="font-size:10px; color:var(--primary-red);">CURRENT FILE LOADED</span>
                                </div>
                                <audio id="audioPlayer" controls style="width: 100%; height: 30px;"></audio>
                            </div>

                            <input type="file" name="audio_file" id="editAudio" class="form-control" accept="audio/*">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" id="editTitle" class="form-control" required>
                    </div>

                    <div class="song-only">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Artist</label>
                                <input type="text" name="artist" id="editArtist" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Album</label>
                                <input type="text" name="album" id="editAlbum" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Duration (Seconds)</label>
                                <input type="number" name="duration_sec" id="editDurationSec" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Language</label>
                                <input type="text" name="language" id="editLanguage" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="movie-only">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Duration (min)</label>
                                <input type="number" name="duration" id="editDuration" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>IMDb Rating</label>
                                <input type="number" step="0.1" name="rating" id="editRating" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Release Year</label>
                                <input type="number" name="year" id="editYear" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Trailer URL (YouTube)</label>
                                <input type="text" name="trailer_url" id="editTrailerUrl" class="form-control" placeholder="https://youtube.com/...">
                            </div>
                        </div>
                        
                        <!-- Play URL සහ Download URL fields Movies වලට පමණක් -->
                        <div class="form-row">
                            <div class="form-group">
                                <label>Play URL</label>
                                <input type="text" name="play_url" id="editPlayUrl" class="form-control" placeholder="https://example.com/play/movie123">
                            </div>
                            <div class="form-group">
                                <label>Download URL</label>
                                <input type="text" name="download_url" id="editDownloadUrl" class="form-control" placeholder="https://example.com/download/movie123">
                            </div>
                        </div>
                    </div>
<div class="form-row">
                    <div class="form-group">
                        <label>Genre</label>
                        
                        <select id="movie_genre" name="genre_movie" class="form-control movie-only">
                            <option value="">Select Genre</option>
                            <option value="Action">Action</option>
                            <option value="Adventure">Adventure</option>
                            <option value="Animation">Animation</option>
                            <option value="Comedy">Comedy</option>
                            <option value="Crime">Crime</option>
                            <option value="Fantasy">Fantasy</option>
                            <option value="History">History</option>
                            <option value="Horror">Horror</option>
                            <option value="Musical">Musical</option>
                            <option value="Mystery">Mystery</option>
                            <option value="Romance">Romance</option>
                            <option value="Sci-Fi">Sci-Fi</option>
                            <option value="Thriller">Thriller</option>
                            <option value="War">War</option>
                        </select>

                        <select id="song_genre" name="genre_song" class="form-control song-only">
                            <option value="">Select Genre</option>
                            <option value="Romantic">Romantic</option>
                            <option value="Sad">Sad</option>
                            <option value="Dance">Dance</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Dj">Dj</option>
                            <option value="Mix">Mix</option>
                            <option value="Mashup">Mashup</option>
                            <option value="Pop">Pop</option>
                            <option value="Rock">Rock</option>
                            <option value="Hip Hop">Hip Hop</option>
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
                        <div class="form-group movie-only">
                                <label>Language</label>
                                <input type="text" name="languagem" id="editLanguagem" class="form-control">
                        </div>
                     </div>
                    <div class="form-group movie-only">
                        <label>Description / Storyline</label>
                        <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 25px;">
                        <button type="submit" class="btn btn-primary" style="flex: 2;">Save Updates</button>
                        <button type="button" class="btn" style="background: #333; flex: 1;" onclick="closeModal()">Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    let allContent = [];
    let csrfToken = '';

    async function loadContent() {
        try {
            const response = await fetch('../library/content_manage_backend.php?t=' + new Date().getTime());
            const data = await response.json();
            if (data.success) {
                allContent = data.content || [];
                csrfToken = data.csrf_token || '';
                document.getElementById('csrfToken').value = csrfToken;
                updateGenreFilterOptions();
                applyFilters();
                
                // Debug: Check if play_url and download_url are coming
                const movies = allContent.filter(item => item.type === 'movie');
                if (movies.length > 0) {
                    console.log('First movie data:', movies[0]);
                    console.log('Play URL:', movies[0].play_url);
                    console.log('Download URL:', movies[0].download_url);
                }
            } else {
                console.error("Failed to load content:", data.message);
                showAlert('Failed to load content: ' + data.message);
            }
        } catch (e) { 
            console.error("Connection error:", e); 
            showAlert('Connection error: ' + e.message);
        }
    }

    function updateGenreFilterOptions() {
        const typeFilter = document.getElementById('typeFilter').value;
        const genreFilter = document.getElementById('genreFilter');
        const currentGenreValue = genreFilter.value;

        genreFilter.innerHTML = '<option value="all">All Genres</option>';
        
        const genres = [...new Set(allContent
            .filter(item => typeFilter === 'all' || item.type === typeFilter)
            .map(item => item.genre)
            .filter(g => g && g !== "")
        )].sort();

        genres.forEach(g => {
            genreFilter.innerHTML += `<option value="${g}">${g}</option>`;
        });

        if (genres.includes(currentGenreValue)) {
            genreFilter.value = currentGenreValue;
        } else {
            genreFilter.value = 'all';
        }
    }

    function handleTypeChange() {
        updateGenreFilterOptions();
        applyFilters();
    }

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const typeTerm = document.getElementById('typeFilter').value;
        const genreTerm = document.getElementById('genreFilter').value;
        const sortTerm = document.getElementById('sortFilter').value;

        let filtered = allContent.filter(item => {
            const matchesSearch = item.title.toLowerCase().includes(searchTerm) || 
                                 (item.artist && item.artist.toLowerCase().includes(searchTerm)) ||
                                 (item.genre && item.genre.toLowerCase().includes(searchTerm));
            const matchesType = typeTerm === 'all' || item.type === typeTerm;
            const matchesGenre = genreTerm === 'all' || item.genre === genreTerm;
            return matchesSearch && matchesType && matchesGenre;
        });

        if (sortTerm === 'newest') {
            filtered.sort((a, b) => (parseInt(b.id) || 0) - (parseInt(a.id) || 0));
        } else if (sortTerm === 'oldest') {
            filtered.sort((a, b) => (parseInt(a.id) || 0) - (parseInt(b.id) || 0));
        } else if (sortTerm === 'az') {
            filtered.sort((a, b) => a.title.localeCompare(b.title));
        }

        displayContent(filtered);
    }

    function displayContent(items) {
        const grid = document.getElementById('contentGrid');
        grid.innerHTML = '';
        
        if (items.length === 0) {
            grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 50px; color: var(--text-gray);">No content found matching your filters.</div>';
            return;
        }

        items.forEach(item => {
            const id = item.id;
            const imageSource = item.type === 'song' ? (item.poster_image) : item.poster_image;
            const imgPath = imageSource ? '../' + imageSource : null;
            const noImageMsg = item.type === 'movie' ? 'No Poster Yet' : 'No Cover Image Yet';

            grid.innerHTML += `
                <div class="content-card">
                    <div class="content-poster">
                        ${imgPath ? `<img src="${imgPath}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">` : ''}
                        <div class="no-image-text" style="${imgPath ? 'display:none' : 'display:block'}">${noImageMsg}</div>
                    </div>
                    <div class="content-info">
                        <h3 class="content-title">${item.title}</h3>
                        <div class="content-meta">
                            <span style="color: var(--primary-red)"><i class="fas ${item.type === 'movie' ? 'fa-film' : 'fa-music'}"></i> ${item.type.toUpperCase()}</span>
                            <span>${item.genre || 'N/A'}</span>
                            ${item.type === 'movie' && item.play_url ? '<span style="color: #10B981;"><i class="fas fa-play"></i> Play URL Available</span>' : ''}
                            ${item.type === 'movie' && item.download_url ? '<span style="color: #3B82F6;"><i class="fas fa-download"></i> Download URL Available</span>' : ''}
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button onclick="openEditModal(${id}, '${item.type}')" class="btn btn-sm" style="background: #2a2a2a;">Edit</button>
                            <button onclick="deleteContent(${id}, '${item.type}')" class="btn btn-sm" style="background: rgba(229,9,20,0.1); color: #ff4d4d;">Delete</button>
                        </div>
                    </div>
                </div>`;
        });
    }

    function openEditModal(id, type) {
        const item = allContent.find(c => c.id == id && c.type == type);
        if (!item) {
            showAlert('Content not found');
            return;
        }
        //ara image eka update aula ta damme
        document.querySelector('input[name="poster_image"]').value = "";

        const modalFrame = document.getElementById('modalFrame');
        modalFrame.className = 'modal-container is-' + type;
        document.getElementById('modalHeadTitle').innerText = 'Edit ' + type;
        document.getElementById('imageLabel').innerText = type === 'movie' ? 'Movie Poster' : 'Cover Image';

        document.getElementById('editId').value = id;
        document.getElementById('editType').value = type;
        document.getElementById('editTitle').value = item.title || "";
        document.getElementById('csrfToken').value = csrfToken;

        const previewImg = document.getElementById('posterPreview');
        const imageSource = item.poster_image;
        
        if (imageSource) {
            previewImg.src = '../' + imageSource;
            previewImg.style.display = 'block';
        } else {
            previewImg.src = "";
            previewImg.style.display = 'none';
        }

        if (type === 'movie') {
            document.getElementById('movie_genre').value = item.genre || "";
            document.getElementById('editLanguagem').value = item.language || ""; 
            document.getElementById('editDuration').value = item.duration || "";
            document.getElementById('editRating').value = item.rating || "";
            document.getElementById('editYear').value = item.year || "";
            document.getElementById('editDescription').value = item.description || "";
            document.getElementById('editTrailerUrl').value = item.trailer_url || "";
            document.getElementById('editPlayUrl').value = item.play_url || "";
            document.getElementById('editDownloadUrl').value = item.download_url || "";
        } else {
            document.getElementById('song_genre').value = item.genre || "";
            document.getElementById('editArtist').value = item.artist || "";
            document.getElementById('editAlbum').value = item.album || ""; 
            document.getElementById('editDurationSec').value = item.duration || "";
            document.getElementById('editLanguage').value = item.language || ""; 

            const audioPlayer = document.getElementById('audioPlayer');
            const audioContainer = document.getElementById('audioPreviewContainer');
            
            if (item.audio_file) {
                audioPlayer.src = '../' + item.audio_file; 
                audioContainer.style.display = 'block';
            } else {
                audioPlayer.src = '';
                audioContainer.style.display = 'none';
            }
        }

        const modal = document.getElementById('editModal');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('editModal');
        const audio = document.getElementById('audioPlayer');
        if(audio) audio.pause();

        modal.classList.remove('active');
        setTimeout(() => { modal.style.display = 'none'; document.body.style.overflow = 'auto'; }, 300);
    }

    function previewFile() {
        const preview = document.getElementById('posterPreview');
        const file = document.getElementById('editImage').files[0];
        const reader = new FileReader();
        reader.onloadend = () => { preview.src = reader.result; preview.style.display = 'block'; }
        if (file) reader.readAsDataURL(file);
    }

    document.getElementById('editModal').addEventListener('click', (e) => { if (e.target.id === 'editModal') closeModal(); });
    document.getElementById('closeModal').onclick = closeModal;

    document.getElementById('updateForm').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const type = document.getElementById('editType').value;
        const finalGenre = type === 'movie' ? formData.get('genre_movie') : formData.get('genre_song');
        formData.append('genre', finalGenre);
        
        // Add CSRF token
        formData.append('csrf_token', csrfToken);

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;

        try {
            const res = await fetch('../library/content_manage_backend.php?action=update', { 
                method: 'POST', 
                body: formData 
            });
            const data = await res.json();
            
            if (data.success) { 
                showAlert('Successfully Updated!'); 
                closeModal(); 
                loadContent(); 
            } else { 
                showAlert('Update Failed: ' + data.message); 
            }
            
            // Update CSRF token
            if (data.csrf_token) {
                csrfToken = data.csrf_token;
            }
        } catch (e) { 
            showAlert('Update Failed: Network error'); 
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    };

    async function deleteContent(id, type) {
        if (!confirm('Are you sure you want to delete this permanently?')) return;
        
        try {
            const res = await fetch(`../library/content_manage_backend.php?action=delete&id=${id}&type=${type}&csrf_token=${csrfToken}`);
            const data = await res.json();
            if (data.success) { 
                showAlert('Content Deleted!'); 
                loadContent(); 
            } else {
                showAlert('Delete Failed: ' + data.message);
            }
        } catch (e) { 
            showAlert('Error deleting: Network error'); 
        }
    }

    function showAlert(msg, type = 'success') {
        const alert = document.createElement('div');
        alert.className = 'alert';
        alert.style.background = type === 'success' ? 'var(--success)' : '#EF4444';
        alert.innerHTML = `<i class="fas fa-info-circle"></i> ${msg}`;
        document.getElementById('alertsContainer').appendChild(alert);
        setTimeout(() => { 
            alert.style.opacity = '0'; 
            setTimeout(() => alert.remove(), 500); 
        }, 3000);
    }

    // Test function
    async function testSystem() {
        console.log('🔍 Testing Content Management System...');
        await loadContent();
        const movies = allContent.filter(item => item.type === 'movie');
        if (movies.length > 0) {
            console.log('✅ System ready. Found', movies.length, 'movies');
            return true;
        }
        return false;
    }

    window.onload = function() {
        loadContent();
        // Auto-test on load (optional)
        // setTimeout(testSystem, 1000);
    };
</script>
</body>
</html>