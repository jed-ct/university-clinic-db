<?php 
include('database.php'); 

// --- CONFIGURATION ---
$records_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $records_per_page;

// --- SEARCH LOGIC ---
$search_term = '';
$where_clause = '';
$bind_types = '';
$bind_params = [];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = trim($_GET['search']);
    $search_clean = str_replace([' ', '.'], '', strtolower($search_term));
    $search_pattern = "%" . $search_clean . "%";
    $where_clause = "WHERE REPLACE(REPLACE(LOWER(CONCAT(DocFirstName, IFNULL(DocMiddleInit, ''), DocLastName)), ' ', ''), '.', '') LIKE ?";
    $bind_types = 's';
    $bind_params[] = &$search_pattern;
}

// --- TOTAL COUNT ---
$total_count_sql = "SELECT COUNT(*) FROM DOCTOR $where_clause";
$count_stmt = $conn->prepare($total_count_sql);
if ($where_clause) $count_stmt->bind_param($bind_types, ...$bind_params);
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_row()[0];
$total_pages = ceil($total_rows / $records_per_page);
$count_stmt->close();

// --- FETCH SPECIALTIES ---
$specialties = [];
$spec_res = $conn->query("SELECT * FROM SPECIALTY ORDER BY SpecialtyName ASC");
if ($spec_res) while($row = $spec_res->fetch_assoc()) $specialties[] = $row;

// --- MAIN DATA QUERY ---
$main_sql = "SELECT d.*, s.SpecialtyName FROM DOCTOR d LEFT JOIN SPECIALTY s ON d.SpecialtyID = s.SpecialtyID $where_clause ORDER BY DocLastName ASC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($main_sql);
$final_bind_types = $bind_types . 'ii';
$final_bind_params = $bind_params;
$final_bind_params[] = &$records_per_page;
$final_bind_params[] = &$offset;

$refs = [&$final_bind_types];
foreach ($final_bind_params as $key => $value) $refs[] = &$final_bind_params[$key];
call_user_func_array([$stmt, 'bind_param'], $refs);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Staff Management</title>
    <link rel="stylesheet" href="./style.css?v=1.1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ea8c838e77.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="header">
        <a id="hyperlink-logo" href="./index.php">
            <div class='header-img' id='logo'>
                <img id='logo-img' src='./img/logo.svg'> TBAClinic
            </div>
        </a>
        <ul class="links">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./consultation.php">Consultations</a></li>
            <li><a href="./patient.php">Patients</a></li>
            <li class="active"><a href="./staff.php">Staff</a></li>
            <li><a href="#footer">Contact</a></li>
        </ul>
        <button id='mobile-menu-btn'><img class='header-img' src='./img/menu.svg'></button>
    </div>

    <div class="staff-table-container">
        <div class="staff-header-title">
            <h2>Clinic Staff Management</h2>
        </div>
        
        <div class="staff-controls">
            <button type="button" class="btn-add-staff" id="add-doctor-modal-btn">
                <i class="fa-solid fa-plus"></i> Add New Staff
            </button>
            
            <div class="staff-search">
                <input type="text" id="doctor-search-input" placeholder="Search staff name..." value="<?= htmlspecialchars($search_term) ?>">
                <div id="autocomplete-results" style="display:none; position:absolute; background:white; border:1px solid #ddd; width:100%; z-index:100;"></div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Specialty</th>
                    <th>Contact No.</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): 
                        $fullName = $row['DocFirstName'] . ' ' . (!empty($row['DocMiddleInit']) ? $row['DocMiddleInit'] . '. ' : '') . $row['DocLastName'];
                    ?>
                        <tr>
                            <td><?= $row['DoctorID'] ?></td>
                            <td><?= htmlspecialchars($fullName) ?></td>
                            <td><?= htmlspecialchars($row['SpecialtyName'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['DocContactNo'] ?? 'N/A') ?></td>
                            <td style="white-space: nowrap;">
                                <button class="action view-btn" 
                                    data-id="<?= $row['DoctorID'] ?>"
                                    data-firstname="<?= htmlspecialchars($row['DocFirstName']) ?>"
                                    data-lastname="<?= htmlspecialchars($row['DocLastName']) ?>"
                                    data-middle="<?= htmlspecialchars($row['DocMiddleInit']) ?>"
                                    data-specialtyname="<?= htmlspecialchars($row['SpecialtyName']) ?>"
                                    data-email="<?= htmlspecialchars($row['DocEmail']) ?>"
                                    data-address="<?= htmlspecialchars($row['DocAddress']) ?>"
                                    data-contact="<?= htmlspecialchars($row['DocContactNo']) ?>"
                                    data-dob="<?= $row['DocDOB'] ?>">View</button>
                                
                                <button class="action edit-btn" 
                                    data-id="<?= $row['DoctorID'] ?>"
                                    data-firstname="<?= htmlspecialchars($row['DocFirstName']) ?>"
                                    data-lastname="<?= htmlspecialchars($row['DocLastName']) ?>"
                                    data-middle="<?= htmlspecialchars($row['DocMiddleInit']) ?>"
                                    data-specialtyid="<?= htmlspecialchars($row['SpecialtyID']) ?>"
                                    data-email="<?= htmlspecialchars($row['DocEmail']) ?>"
                                    data-address="<?= htmlspecialchars($row['DocAddress']) ?>"
                                    data-contact="<?= htmlspecialchars($row['DocContactNo']) ?>"
                                    data-dob="<?= $row['DocDOB'] ?>">Edit</button>
                                
                                <a href="staff_delete.php?id=<?= $row['DoctorID'] ?>" class="action delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No staff found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php 
                $base_url = "staff.php?" . ($search_term ? "search=" . urlencode($search_term) . "&" : "");
                if ($current_page > 1) echo "<a href='{$base_url}page=".($current_page-1)."'>&laquo; Previous</a>";
                echo "<div>Page $current_page of $total_pages</div>";
                if ($current_page < $total_pages) echo "<a href='{$base_url}page=".($current_page+1)."'>Next &raquo;</a>";
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="addStaffModal" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <button class="close-modal-btn" onclick="closeAddStaffModal()">&times;</button>
            <h3 class="text-center">Add New Staff</h3>
            <form action="staff_create.php" method="POST" id="addStaffForm">
                <div class="form-row">
                    <div class="col"><label>First Name</label><input type="text" class="form-control" name="firstname" required></div>
                    <div class="col"><label>Last Name</label><input type="text" class="form-control" name="lastname" required></div>
                    <div class="col-3"><label>M.I.</label><input type="text" class="form-control" name="middleinit" maxlength="2"></div>
                </div>
                <div class="form-group mt-2">
                    <label>Specialty</label>
                    <select class="form-control" name="specialtyid" required>
                        <option value="" disabled selected>Select Specialty</option>
                        <?php foreach($specialties as $spec) echo "<option value='{$spec['SpecialtyID']}'>{$spec['SpecialtyName']}</option>"; ?>
                    </select>
                </div>
                <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email"></div>
                <div class="form-group"><label>Address</label><input type="text" class="form-control" name="address"></div>
                <div class="form-row">
                    <div class="col"><label>Contact</label><input type="text" class="form-control" name="contact"></div>
                    <div class="col"><label>DOB</label><input type="date" class="form-control" name="dob"></div>
                </div>
                <div class="text-right mt-3">
                    <button type="button" class="btn btn-secondary" onclick="closeAddStaffModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="viewStaffModal" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <button class="close-modal-btn" onclick="closeViewModal()">&times;</button>
            <h3 class="text-center">Staff Details</h3>
            <hr>
            <p><strong>Name:</strong> <span id="view-fullname"></span></p>
            <p><strong>Specialty:</strong> <span id="view-specialty"></span></p>
            <p><strong>Email:</strong> <span id="view-email"></span></p>
            <p><strong>Contact:</strong> <span id="view-contact"></span></p>
            <p><strong>Address:</strong> <span id="view-address"></span></p>
            <div class="text-right mt-3"><button class="btn btn-secondary" onclick="closeViewModal()">Close</button></div>
        </div>
    </div>

    <div id="editStaffModal" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <button class="close-modal-btn" onclick="closeEditModal()">&times;</button>
            <h3 class="text-center">Edit Staff</h3>
            <form action="staff_update.php" method="POST">
                <input type="hidden" name="id" id="edit-id">
                <div class="form-row">
                    <div class="col"><label>First Name</label><input type="text" class="form-control" name="firstname" id="edit-firstname" required></div>
                    <div class="col"><label>Last Name</label><input type="text" class="form-control" name="lastname" id="edit-lastname" required></div>
                    <div class="col-3"><label>M.I.</label><input type="text" class="form-control" name="middleinit" id="edit-middle"></div>
                </div>
                <div class="form-group mt-2">
                    <label>Specialty</label>
                    <select class="form-control" name="specialtyid" id="edit-specialty" required>
                        <?php foreach($specialties as $spec) echo "<option value='{$spec['SpecialtyID']}'>{$spec['SpecialtyName']}</option>"; ?>
                    </select>
                </div>
                <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" id="edit-email"></div>
                <div class="form-group"><label>Address</label><input type="text" class="form-control" name="address" id="edit-address"></div>
                <div class="form-row">
                    <div class="col"><label>Contact</label><input type="text" class="form-control" name="contact" id="edit-contact"></div>
                    <div class="col"><label>DOB</label><input type="date" class="form-control" name="dob" id="edit-dob"></div>
                </div>
                <div class="text-right mt-3">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="footer">basta contact info</div>
    
    <?php 
    if ($stmt) $stmt->close();
    $conn->close(); 
    ?>
    <script src="./staff.js"></script>
</body>
</html>