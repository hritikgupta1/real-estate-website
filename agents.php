<?php
$page_title = "Our Agents - Dubai Space";
include 'db.php';
include 'header.php';

// Handle Add Agent
if (isset($_POST['add_agent'])) {
    $name  = $_POST['name'];
    $role  = $_POST['role'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $targetDir = "images/agents/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $imageName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $imageName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $stmt = $pdo->prepare("INSERT INTO agents (name, role, phone, email, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $role, $phone, $email, $targetFile]);
        echo "<script>alert(' Agent added successfully!'); window.location.href='agents.php';</script>";
    } else {
        echo "<script>alert(' Error uploading image.');</script>";
    }
}

// Handle Delete Agent
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    // 🔹 Get old image before deleting
    $stmt = $pdo->prepare("SELECT image FROM agents WHERE id = ?");
    $stmt->execute([$id]);
    $oldAgent = $stmt->fetch();

    $stmt = $pdo->prepare("DELETE FROM agents WHERE id = ?");
    $stmt->execute([$id]);

    // 🔹 Delete old image file
    if ($oldAgent && !empty($oldAgent['image']) && file_exists($oldAgent['image'])) {
        unlink($oldAgent['image']);
    }

    echo "<script>alert(' Agent deleted!'); window.location.href='agents.php';</script>";
}

// Handle Edit Agent
if (isset($_POST['edit_agent'])) {
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $role  = $_POST['role'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    if (!empty($_FILES["image"]["name"])) {
        // 🔹 Get old image before updating
        $stmt = $pdo->prepare("SELECT image FROM agents WHERE id = ?");
        $stmt->execute([$id]);
        $oldAgent = $stmt->fetch();

        $targetDir = "images/agents/";
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

        $stmt = $pdo->prepare("UPDATE agents SET name=?, role=?, phone=?, email=?, image=? WHERE id=?");
        $stmt->execute([$name, $role, $phone, $email, $targetFile, $id]);

        // 🔹 Delete old image file
        if ($oldAgent && !empty($oldAgent['image']) && file_exists($oldAgent['image'])) {
            unlink($oldAgent['image']);
        }
    } else {
        $stmt = $pdo->prepare("UPDATE agents SET name=?, role=?, phone=?, email=? WHERE id=?");
        $stmt->execute([$name, $role, $phone, $email, $id]);
    }

    echo "<script>alert(' Agent updated successfully!'); window.location.href='agents.php';</script>";
}
?>

<style>
    /* Modal Styles */

    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        inset: 0;
        /* shorthand for top:0; left:0; right:0; bottom:0 */
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        padding: 20px;
        overflow-y: auto;
        /* allow scroll on small screens */
    }
    .modal-content {
        background: #fff;
        margin: 40px auto;
        padding: 20px 25px;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        animation: fadeIn 0.3s ease;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        /* scroll inside modal if content too long */
    }

    /* Modal Heading */
    .modal-content h2 {
        margin-bottom: 12px;
        font-size: 22px;
        font-weight: bold;
        text-align: center;
        color: #111827;
    }

    /* Form Layout */
    .modal-content form {
        display: flex;
        flex-direction: column;
        gap: 8px;
        /* reduced gap */
    }

    /* Input Fields */
    .modal-content input[type="text"],
    .modal-content input[type="tel"],
    .modal-content input[type="email"],
    .modal-content input[type="file"] {
        padding: 9px 10px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        transition: border 0.2s ease;
    }

    .modal-content input:focus {
        border-color: #0b1220;
        outline: none;
        box-shadow: 0 0 0 2px rgba(11, 18, 32, 0.2);
    }

    /* Buttons */
    .modal-content button {
        padding: 10px;
        border: none;
        border-radius: 6px;
        background: #0b1220;
        color: #fff;
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        transition: background 0.2s ease, transform 0.1s ease;
    }

    .modal-content button:hover {
        background: #1f2937;
    }

    .modal-content button:active {
        transform: scale(0.97);
    }

    /* Close Button */
    .close {
        position: absolute;
        top: 12px;
        right: 16px;
        font-size: 24px;
        font-weight: bold;
        color: #555;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .close:hover {
        color: #000;
    }

    /* Action buttons in cards */
    .card .actions {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 10px;
    }

    /* Simple fade animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<section class="container">
    <h2>Meet Our Agents</h2>
    <p>Our professional real estate agents are here to help you find your dream property.</p>

    <!-- Button to open Add Agent Modal -->
    <div class="container" style="text-align:right; margin-bottom:20px;">
        <button onclick="document.getElementById('addAgentModal').style.display='block'" class="btn btn-primary" style="cursor: pointer;">➕ Add New Agent</button>
    </div>

    <!-- Add Agent Modal -->
    <div id="addAgentModal" class="modal">
        <div class="modal-content">
            <span onclick="document.getElementById('addAgentModal').style.display='none'" class="close">&times;</span>
            <h2>Add New Agent</h2>
            <form method="POST" enctype="multipart/form-data">

                <label for="add_name">Full Name</label>
                <input type="text" id="add_name" name="name" placeholder="e.g. John Doe" required>

                <label for="add_role">Role</label>
                <input type="text" id="add_role" name="role" placeholder="e.g. Senior Property Consultant" required>

                <label for="add_phone">Phone</label>
                <input type="tel" id="add_phone" name="phone" placeholder="e.g. 98765 43210" pattern="[0-9]{10}" inputmode="numeric" required>

                <label for="add_email">Email</label>
                <input type="email" id="add_email" name="email" placeholder="e.g. john.doe@email.com" required>

                <label for="add_image">Profile Image</label>
                <input type="file" id="add_image" name="image" accept="image/*" onchange="previewAddImage(event)" required>
                <img id="add_preview" src="" style="width:100%;max-height:200px;margin-top:10px;border-radius:8px;object-fit:cover;display:none;">

                <button type="submit" name="add_agent">Add Agent</button>
            </form>
        </div>
    </div>

    <!-- Edit Agent Modal -->
    <div id="editAgentModal" class="modal">
        <div class="modal-content">
            <span onclick="document.getElementById('editAgentModal').style.display='none'" class="close">&times;</span>
            <h2>Edit Agent</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">

                <label for="edit_name">Full Name</label>
                <input type="text" name="name" id="edit_name" placeholder="e.g. John Doe" required>

                <label for="edit_role">Role</label>
                <input type="text" name="role" id="edit_role" placeholder="e.g. Senior Property Consultant" required>

                <label for="edit_phone">Phone</label>
                <input type="tel" name="phone" id="edit_phone" placeholder="e.g. 98765 43210" pattern="[0-9]{10}" inputmode="numeric" required>

                <label for="edit_email">Email</label>
                <input type="email" name="email" id="edit_email" placeholder="e.g. john.doe@email.com" required>

                <label for="edit_image">Profile Image</label>
                <input type="file" name="image" id="edit_image" onchange="previewEditImage(event)">
                <img id="edit_preview" src="" style="width:100%;max-height:200px;margin-top:10px;border-radius:8px;object-fit:cover;">

                <button type="submit" name="edit_agent">Update Agent</button>
            </form>
        </div>
    </div>


    <!-- Agent Cards -->
    <div class="grid-3" style="gap:20px; margin-top:30px;">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM agents ORDER BY id DESC");
        $stmt->execute();
        foreach ($stmt as $agent): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($agent['image']) ?>" alt="<?= htmlspecialchars($agent['name']) ?>" style="width:100%; border-radius:10px; height:250px; object-fit:cover;">
                <h3><?= htmlspecialchars($agent['name']) ?></h3>
                <p class="muted" style="color: black;"><?= htmlspecialchars($agent['role']) ?></p>
                <p>📞 <?= htmlspecialchars($agent['phone']) ?></p>
                <p>✉️ <?= htmlspecialchars($agent['email']) ?></p>

                <div class="actions">
                    <button class="btn btn-primary"
                        onclick="openEditModal(
              <?= $agent['id'] ?>,
              '<?= htmlspecialchars($agent['name']) ?>',
              '<?= htmlspecialchars($agent['role']) ?>',
              '<?= htmlspecialchars($agent['phone']) ?>',
              '<?= htmlspecialchars($agent['email']) ?>',
              '<?= htmlspecialchars($agent['image']) ?>'
            )"> Edit</button>

                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this agent?');">
                        <input type="hidden" name="delete_id" value="<?= $agent['id'] ?>">
                        <button type="submit" class="btn btn-danger"> Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
    function openEditModal(id, name, role, phone, email, image) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_preview').src = image;
        document.getElementById('editAgentModal').style.display = 'block';
    }

    function previewEditImage(event) {
        const preview = document.getElementById('edit_preview');
        preview.src = URL.createObjectURL(event.target.files[0]);
    }

    function previewAddImage(event) {
        const preview = document.getElementById('add_preview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.style.display = "block";
    }
</script>

<?php include 'footer.php'; ?>