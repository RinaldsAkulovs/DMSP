<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function getSongsFromDatabase() {
    $conn = mysqli_connect('localhost', 'root', '', 'pulseplay');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT * FROM songs";
    $result = mysqli_query($conn, $sql);
    $songs = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $songs[] = $row;
        }
    }
    mysqli_close($conn);
    return $songs;
}

function deleteSongFromDatabase($songId) {
    $conn = mysqli_connect('localhost', 'root', '', 'pulseplay');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "DELETE FROM songs WHERE id = $songId";
    if (!mysqli_query($conn, $sql)) {
        echo "Error deleting song: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}

function updateSongInDatabase($songId, $title, $artist, $album, $genre, $duration, $path, $albumOrder, $plays) {
    $conn = mysqli_connect('localhost', 'root', '', 'pulseplay');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "UPDATE songs SET title='$title', artist='$artist', album='$album', genre='$genre', duration='$duration', path='$path', albumOrder='$albumOrder', plays='$plays' WHERE id=$songId";
    if (!mysqli_query($conn, $sql)) {
        echo "Error updating song: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}

if (isset($_POST['deleteButton']) && isset($_POST['songIdToDelete'])) {
    $songIdToDelete = $_POST['songIdToDelete'];
    deleteSongFromDatabase($songIdToDelete);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['addSong'])) {
    $conn = mysqli_connect('localhost', 'root', '', 'pulseplay');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $album = $_POST['album'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $path = $_POST['path'];
    $albumOrder = $_POST['albumOrder'];
    $plays = $_POST['plays'];

    $sql = "INSERT INTO songs (title, artist, album, genre, duration, path, albumOrder, plays) VALUES ('$title', '$artist', '$album', '$genre', '$duration', '$path', '$albumOrder', '$plays')";
    if (mysqli_query($conn, $sql)) {
        echo "Song added successfully";
    } else {
        echo "Error adding song: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['editSong'])) {
    $songId = $_POST['songId'];
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $album = $_POST['album'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $path = $_POST['path'];
    $albumOrder = $_POST['albumOrder'];
    $plays = $_POST['plays'];

    updateSongInDatabase($songId, $title, $artist, $album, $genre, $duration, $path, $albumOrder, $plays);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$songs = getSongsFromDatabase();
include("includes/includedFilesAdmin.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            font-size: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            padding: 8px;
            border: 3px solid #ddd;
            text-align: left;
        }

        tr:hover {
            background-color: #282828;
        }

        .add-song-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
            display: inline-block;
        }

        .add-song-button:hover {
            background-color: #45a049;
        }

        .form-container {
            display: none;
            background-color: #282828;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .form-container label {
            margin-bottom: 10px;
            display: block;
        }

        .form-container input[type=text], .form-container input[type=email], .form-container input[type=password], .form-container input[type=date], .form-container input[type=number] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            color: black;
        }

        .form-container button[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Song List</h2>

    <button class="add-song-button" id="toggleAddSongForm">Add Song</button>

    <div class="form-container" id="addSongForm">
        <h2>Add New Song</h2>
        <form method="post" action="">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br>

            <label for="artist">Artist:</label>
            <input type="text" id="artist" name="artist" required><br>

            <label for="album">Album:</label>
            <input type="text" id="album" name="album" required><br>

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" required><br>

            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" required><br>

            <label for="path">Path:</label>
            <input type="text" id="path" name="path" required><br>

            <label for="albumOrder">Album Order:</label>
            <input type="number" id="albumOrder" name="albumOrder"><br>

            <label for="plays">Plays:</label>
            <input type="number" id="plays" name="plays"><br>

            <button type="submit" name="addSong">Add Song</button>
            <button type="button" class="add-song-button" id="closeAddSongForm">Close</button>
        </form>
    </div>

    <div class="form-container" id="editSongForm">
        <h2>Edit Song</h2>
        <form method="post" action="">
            <input type="hidden" id="songId" name="songId" required>
            <label for="title">Title:</label>
            <input type="text" id="editTitle" name="title" required><br>

            <label for="artist">Artist:</label>
            <input type="text" id="editArtist" name="artist" required><br>

            <label for="album">Album:</label>
            <input type="text" id="editAlbum" name="album" required><br>

            <label for="genre">Genre:</label>
            <input type="text" id="editGenre" name="genre" required><br>

            <label for="duration">Duration:</label>
            <input type="text" id="editDuration" name="duration" required><br>

            <label for="path">Path:</label>
            <input type="text" id="editPath" name="path" required><br>

            <label for="albumOrder">Album Order:</label>
            <input type="number" id="editAlbumOrder" name="albumOrder"><br>

            <label for="plays">Plays:</label>
            <input type="number" id="editPlays" name="plays"><br>

            <button type="submit" name="editSong">Save Changes</button>
            <button type="button" class="add-song-button" id="closeEditSongForm">Close</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Genre</th>
                <th>Duration</th>
                <th>Path</th>
                <th>Album Order</th>
                <th>Plays</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($songs as $song): ?>
            <tr>
                <td><?php echo $song['id']; ?></td>
                <td><?php echo $song['title']; ?></td>
                <td><?php echo $song['artist']; ?></td>
                <td><?php echo $song['album']; ?></td>
                <td><?php echo $song['genre']; ?></td>
                <td><?php echo $song['duration']; ?></td>
                <td><?php echo $song['path']; ?></td>
                <td><?php echo $song['albumOrder']; ?></td>
                <td><?php echo $song['plays']; ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="songIdToDelete" value="<?php echo $song['id']; ?>">
                        <button type="submit" class="add-song-button" name="deleteButton">Delete</button>
                    </form>
                    <button class="add-song-button" onclick="editSong(<?php echo $song['id']; ?>, '<?php echo $song['title']; ?>', '<?php echo $song['artist']; ?>', '<?php echo $song['album']; ?>', '<?php echo $song['genre']; ?>', '<?php echo $song['duration']; ?>', '<?php echo $song['path']; ?>', '<?php echo $song['albumOrder']; ?>', '<?php echo $song['plays']; ?>')">Edit</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        document.getElementById('toggleAddSongForm').addEventListener('click', function() {
            var formContainer = document.getElementById('addSongForm');
            if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        });

        document.getElementById('closeAddSongForm').addEventListener('click', function() {
            document.getElementById('addSongForm').style.display = 'none';
        });

        document.getElementById('closeEditSongForm').addEventListener('click', function() {
            document.getElementById('editSongForm').style.display = 'none';
        });

        function editSong(id, title, artist, album, genre, duration, path, albumOrder, plays) {
            document.getElementById('songId').value = id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editArtist').value = artist;
            document.getElementById('editAlbum').value = album;
            document.getElementById('editGenre').value = genre;
            document.getElementById('editDuration').value = duration;
            document.getElementById('editPath').value = path;
            document.getElementById('editAlbumOrder').value = albumOrder;
            document.getElementById('editPlays').value = plays;

            var formContainer = document.getElementById('editSongForm');
            formContainer.style.display = 'block';
        }
    </script>
</body>
</html>