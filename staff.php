<?php 
include('database.php'); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- CONFIGURATION ---
$records_per_page = 10;

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $records_per_page;

// --- INITIALIZE FILTERS ---
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_specialty = isset($_GET['filter_specialty']) ? $_GET['filter_specialty'] : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : 'Active'; 
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'newest';

// --- BUILD QUERY PARTS ---
$where_clauses = [];
$bind_types = '';
$bind_params = [];

// 1. Search
if (!empty($search_term)) {
    $search_clean = str_replace([' ', '.'], '', strtolower($search_term));
    $search_pattern = "%" . $search_clean . "%";
    $where_clauses[] = "(REPLACE(REPLACE(LOWER(CONCAT(D.DocFirstName, IFNULL(D.DocMiddleInit, ''), D.DocLastName)), ' ', ''), '.', '') LIKE ?)";
    $bind_types .= 's';
    $bind_params[] = $search_pattern;
}

// 2. Specialty
if (!empty($filter_specialty)) {
    $where_clauses[] = "D.DoctorID IN (SELECT DoctorID FROM DOCTOR_SPECIALTY WHERE SpecialtyID = ?)";
    $bind_types .= 'i';
    $bind_params[] = $filter_specialty;
}

// 3. Status
if ($filter_status === 'Active') {
    $where_clauses[] = "D.IsActive = 1";
} elseif ($filter_status === 'Inactive') {
    $where_clauses[] = "D.IsActive = 0";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(' AND ', $where_clauses);
}

// 4. Sorting
// 4. Sorting
$order_sql = "ORDER BY D.DoctorID DESC"; // Default
switch ($sort_by) {
    case 'name_asc':   $order_sql = "ORDER BY D.DocFirstName ASC, D.DocLastName ASC"; break;
    case 'name_desc':  $order_sql = "ORDER BY D.DocFirstName DESC, D.DocLastName DESC"; break;
    case 'email_asc':  $order_sql = "ORDER BY D.DocEmail ASC"; break;
    case 'email_desc': $order_sql = "ORDER BY D.DocEmail DESC"; break;
    case 'newest':     $order_sql = "ORDER BY D.DoctorID DESC"; break;
    case 'oldest':     $order_sql = "ORDER BY D.DoctorID ASC"; break;
}
// --- QUERIES ---
$count_sql = "SELECT COUNT(*) FROM DOCTOR D $where_sql";
$count_stmt = $conn->prepare($count_sql);
if (!empty($bind_params)) {
    $count_stmt->bind_param($bind_types, ...$bind_params);
}
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_row()[0];
$total_pages = ceil($total_rows / $records_per_page);
$count_stmt->close();

$sql = "SELECT D.*, 
        GROUP_CONCAT(S.SpecialtyName SEPARATOR ', ') as SpecialtyNames,
        GROUP_CONCAT(S.SpecialtyID) as SpecialtyIDs
        FROM DOCTOR D
        LEFT JOIN DOCTOR_SPECIALTY DS ON D.DoctorID = DS.DoctorID
        LEFT JOIN SPECIALTY S ON DS.SpecialtyID = S.SpecialtyID
        $where_sql 
        GROUP BY D.DoctorID
        $order_sql 
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);

$types = $bind_types . "ii";
$params = $bind_params; 
$params[] = $offset;
$params[] = $records_per_page;

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$specialties_result = $conn->query("SELECT * FROM SPECIALTY ORDER BY SpecialtyName ASC");
$specialties = [];
if($specialties_result) {
    while($row = $specialties_result->fetch_assoc()) {
        $specialties[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://kit.fontawesome.com/ea8c838e77.js" crossorigin="anonymous"></script>
    <style>
        .modal { 
            z-index: 10000 !important; 
            background-color: rgba(0,0,0,0.6) !important; 
            display: none; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content { 
            background-color: #fff;
            margin: auto; 
            max-width: 650px !important; 
            width: 90%; 
            padding: 0; 
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            position: relative; 
            max-height: 90vh; 
            overflow-y: auto; 
            overflow-x: hidden;
        }

        .close-btn-div {
            background-color: var(--color-primary); /* Teal Background */
            color: white;
            display: flex;
            justify-content: space-between; 
            align-items: center;
            padding: 15px 20px; 
            border-radius: 8px 8px 0 0; 
            margin-bottom: 0;
        }

        .close-btn-div h3 {
            margin: 0;
            color: white !important; 
            font-weight: 600;
            font-size: 1.1rem; 
        }

        /* Close Button */
        .close-modal-btn.close-btn-patient {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-img {
            width: 20px;
            height: 20px;
            filter: brightness(0) invert(1); /* White Icon */
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        
        .btn-img:hover {
            opacity: 1; 
        }

        /* Padding for content */
        .modal-message {
            padding: 25px 25px 10px 25px;
        }

        .consultation-modal-actions {
            display: flex;
            justify-content: flex-end; 
            gap: 10px;
            padding: 15px 25px 25px 25px; 
        }

        /* --- FORM ELEMENTS --- */
        .forms-input label { font-weight: 600; color: #444; margin-bottom: 5px; display: block; font-size: 0.9rem; }
        .forms-input input, .forms-input select { border: 1px solid #aaa !important; border-radius: 4px; padding: 8px; font-size: 1rem; color: #333; background-color: #fff; }
        .checkbox-container { border: 1px solid #aaa; border-radius: 4px; padding: 10px; max-height: 150px; overflow-y: auto; background: #fff; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .checkbox-item { display: flex; align-items: center; gap: 8px; font-size: 0.95rem; cursor: pointer; color: #333; }

        /* --- LAYOUT --- */
        select[multiple] { height: 120px; border: 1px solid #ccc; padding: 5px; border-radius: 4px; }
        
        /* Filter Bar - aligned to bottom */
        .filter-bar { display: flex; gap: 15px; margin-bottom: 20px; align-items: flex-end; flex-wrap: wrap; background: #f8f9fa; padding: 12px; border-radius: 8px; border: 1px solid #ddd; }
        .filter-group { display: flex; flex-direction: column; gap: 4px; font-size: 1rem; }
        .filter-select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; min-width: 150px; font-size: 1rem; height: 38px; }
        .search-wrapper { flex-grow: 1; display: flex; justify-content: flex-end; }
        .search-wrapper input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 100%; max-width: 280px; font-size: 1rem; height: 38px; }
        
        .consultations-table th, .consultations-table td { padding: 12px 15px !important; font-size: 1rem; }
        
        /* BUTTON COLORS */
        .action { padding: 6px 10px; font-size: 0.9rem; border-radius: 4px; border: none; cursor: pointer; color: white; }
        .view-btn { background-color: #17a2b8; }
        .edit-btn { background-color: #0c7878; color: white; } 
        .edit-btn:hover { background-color: #004c4c; }
        .delete-btn { background-color: #dc3545; }

        #add-doctor-modal-btn { 
            background-color: #0c7878; 
            color: #ffffff !important; 
            height: 38px; 
            padding: 0 20px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 1rem;
            font-weight: 700; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background-color 0.2s;
        }
        
        #add-doctor-modal-btn:hover { background-color: #004c4c; }

        /* Profile Styles */
        .staff-profile-card { display: flex; flex-direction: column; gap: 20px; padding: 10px; color: #333; }
        .profile-header { display: flex; align-items: center; gap: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px; }
        .profile-avatar { width: 70px; height: 70px; min-width: 70px; background-color: #0c7878; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
        .profile-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .detail-label { font-size: 0.8rem; text-transform: uppercase; color: #888; font-weight: 700; display: block;}
        .detail-value { font-size: 1.05rem; font-weight: 500; color: #222; border-bottom: 1px dashed #e0e0e0; padding-bottom: 5px; word-break: break-word;}
        
        @media (max-width: 768px) { .filter-bar { flex-direction: column; align-items: stretch; } .profile-grid { grid-template-columns: 1fr; } }

    .main-content-container {
        display: flex !important;
        align-items: flex-start !important; /* Aligns content to top */
        min-height: 100vh; /* Ensures it covers full screen height */
        box-sizing: border-box; /* Includes padding in height calculation */
    }
    
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    
    #footer {
        margin-top: auto; /* Pushes footer to bottom if content is short */
    }
    </style>
</head>
<body>

    <div class="header">
        <a id="hyperlink-logo" href="./index.php"><div class='header-img' id='logo'><img id='logo-img' src='./img/logo.svg'> TBAClinic</div></a>
        <ul class="links">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./consultation.php">Consultations</a></li>
            <li><a href="./patient.php">Patients</a></li>
            <li><a href="./staff.php">Staff</a></li>
            <li><a href="#footer">Contact</a></li>
        </ul>
        <button id='mobile-menu-btn'><img class='header-img' src='./img/menu.svg'></button>
    </div>

    <div class='main-content-container'>
        <div class='main-content'>
            <div class="patient-table-container">
                <div><h2 class='consultation-history'>Staff Management</h2></div>
            </div>

            <div class="filter-bar">
                <div class="filter-group">
                    <label>Specialty</label>
                    <select id="filter-specialty" class="filter-select">
                        <option value="">All Specialties</option>
                        <?php foreach($specialties as $spec): ?>
                            <option value="<?php echo $spec['SpecialtyID']; ?>" <?php echo ($filter_specialty == $spec['SpecialtyID']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($spec['SpecialtyName']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Status</label>
                    <select id="filter-status" class="filter-select">
                        <option value="Active" <?php echo ($filter_status == 'Active') ? 'selected' : ''; ?>>Active Only</option>
                        <option value="Inactive" <?php echo ($filter_status == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                        <option value="All" <?php echo ($filter_status == 'All') ? 'selected' : ''; ?>>All</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label style="visibility:hidden; display:block; margin-bottom:5px;">Add</label> 
                    <button class="action" id="add-doctor-modal-btn">
                        <i class="fa-solid fa-plus"></i> Add New Staff
                    </button>
                </div>

                <select id="sort-by" style="display:none;">
                    <option value="newest" <?php echo ($sort_by == 'newest') ? 'selected' : ''; ?>>Newest</option>
                    <option value="oldest" <?php echo ($sort_by == 'oldest') ? 'selected' : ''; ?>>Oldest</option>
                    <option value="name_asc" <?php echo ($sort_by == 'name_asc') ? 'selected' : ''; ?>>Name (A-Z)</option>
                    <option value="name_desc" <?php echo ($sort_by == 'name_desc') ? 'selected' : ''; ?>>Name (Z-A)</option>
                    <option value="email_asc" <?php echo ($sort_by == 'email_asc') ? 'selected' : ''; ?>>Email (A-Z)</option>
                    <option value="email_desc" <?php echo ($sort_by == 'email_desc') ? 'selected' : ''; ?>>Email (Z-A)</option>
                </select>
                <div class="search-wrapper">
                    <div class="filter-group" style="width: 100%; align-items: flex-end;">
                        <input type="text" id="doctor-search-input" placeholder="Search doctor name..." value="<?php echo htmlspecialchars($search_term); ?>">
                    </div>
                </div>
            </div>

            <table class="consultations-table" style=" width: 100%;">
<thead>
    <tr>
        <?php 
            // Calculate next sort state for Name
            $nameSort = ($sort_by === 'name_asc') ? 'name_desc' : 'name_asc';
            $nameIcon = 'fa-sort';
            if($sort_by === 'name_asc') $nameIcon = 'fa-sort-up';
            if($sort_by === 'name_desc') $nameIcon = 'fa-sort-down';

            // Calculate next sort state for Email
            $emailSort = ($sort_by === 'email_asc') ? 'email_desc' : 'email_asc';
            $emailIcon = 'fa-sort';
            if($sort_by === 'email_asc') $emailIcon = 'fa-sort-up';
            if($sort_by === 'email_desc') $emailIcon = 'fa-sort-down';
        ?>

        <th class="sortable" data-sort="<?php echo $nameSort; ?>" style="width: 25%; cursor: pointer;">
            Name <i class="fa-solid <?php echo $nameIcon; ?>"></i>
        </th>
        
        <th style="width: 20%;">Specialties</th>
        
        <th data-sort="<?php echo $emailSort; ?>" style="width: 25%; cursor: pointer;">
            Email
        </th>
        
        <th style="width: 15%;">Contact</th>
        <th style="width: 15%;">Actions</th>
    </tr>
</thead>
<tbody>
    <?php 
    $row_count = 0; 
    if ($result->num_rows > 0): 
        while($row = $result->fetch_assoc()): 
            $row_count++;
            $firstName = $row['DocFirstName'];
            $lastName = $row['DocLastName'];
            $middleInit = $row['DocMiddleInit'] ?? '';
            $email = isset($row['DocEmail']) ? $row['DocEmail'] : (isset($row['Email']) ? $row['Email'] : '');
            $contact = isset($row['DocContactNo']) ? $row['DocContactNo'] : (isset($row['ContactNo']) ? $row['ContactNo'] : (isset($row['Contact']) ? $row['Contact'] : ''));
            $address = $row['DocAddress'] ?? $row['Address'] ?? '';
            $dob = $row['DocDOB'] ?? $row['DOB'] ?? '';
            $sex = $row['DocSex'] ?? $row['Sex'] ?? '';
    ?>
            <tr style="height: 55px;"> 
                <td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($firstName . ' ' . $middleInit . ' ' . $lastName); ?></td>
                <td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($row['SpecialtyNames'] ?? 'None'); ?></td>
                <td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($email); ?></td>
                <td><?php echo htmlspecialchars($contact); ?></td>
                <td style="white-space: nowrap;">
                    <?php if($row['IsActive'] == 0): ?>
                        <button class="action view" style="background-color:#6c757d; cursor:default;">Inactive</button>
                    <?php else: ?>
                        <button class="action view view-btn" 
                            data-name="<?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>"
                            data-email="<?php echo htmlspecialchars($email); ?>"
                            data-specialty="<?php echo htmlspecialchars($row['SpecialtyNames'] ?? 'None'); ?>"
                            data-address="<?php echo htmlspecialchars($address); ?>"
                            data-contact="<?php echo htmlspecialchars($contact); ?>"
                            data-dob="<?php echo htmlspecialchars($dob); ?>"
                            data-sex="<?php echo htmlspecialchars($sex); ?>">
                            View
                        </button>
                        <button class="action edit edit-btn" 
                            data-id="<?php echo $row['DoctorID']; ?>"
                            data-first="<?php echo htmlspecialchars($firstName); ?>"
                            data-last="<?php echo htmlspecialchars($lastName); ?>"
                            data-middle="<?php echo htmlspecialchars($middleInit); ?>"
                            data-specialtyids="<?php echo $row['SpecialtyIDs']; ?>" 
                            data-email="<?php echo htmlspecialchars($email); ?>"
                            data-address="<?php echo htmlspecialchars($address); ?>"
                            data-contact="<?php echo htmlspecialchars($contact); ?>"
                            data-dob="<?php echo htmlspecialchars($dob); ?>"
                            data-sex="<?php echo htmlspecialchars($sex); ?>">
                            Edit
                        </button>
                        <a href="staff_delete.php?id=<?php echo $row['DoctorID']; ?>" class="action delete" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr style="height: 55px;"><td colspan="5" style="text-align:center;">No staff found.</td></tr>
    <?php endif; ?>
    
    </tbody>
            </table>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php 
                        $query_params = $_GET; 
                        function build_url($page, $params) {
                            $params['page'] = $page;
                            return '?' . http_build_query($params);
                        }
                    ?>
                    <?php if ($current_page > 1): ?>
                        <a href="<?php echo build_url($current_page - 1, $query_params); ?>">&laquo; Prev</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?php echo build_url($i, $query_params); ?>" class="<?php echo ($i == $current_page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <?php if ($current_page < $total_pages): ?>
                        <a href="<?php echo build_url($current_page + 1, $query_params); ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="addStaffModal" class="modal" hidden>
        <div class="modal-content"> 
            <div class="close-btn-div">
                <h3>Add New Staff</h3>
                <button type="button" class="close-modal-btn close-btn-patient"><img class='btn-img' src="./img/close.svg"></button>
            </div>
            <div class="modal-message">
                <form action="staff_create.php" method="POST" id="addStaffForm">
                    <div style="display:flex; gap:10px; margin-bottom:10px;">
                        <div class="forms-input" style="flex:1;"><label>First Name</label><input type="text" name="firstname" maxlength="50" required></div>
                        <div class="forms-input" style="flex:1;"><label>Last Name</label><input type="text" name="lastname" maxlength="50" required></div>
                        <div class="forms-input" style="width:50px;"><label>M.I.</label><input type="text" name="middleinit" maxlength="1" size="3"></div>
                    </div>
                    <div class="forms-input">
                        <label>Specialties</label>
                        <div class="checkbox-container">
                            <?php foreach($specialties as $spec): ?>
                                <label class="checkbox-item"><input type="checkbox" name="specialtyids[]" value="<?php echo $spec['SpecialtyID']; ?>"> <?php echo htmlspecialchars($spec['SpecialtyName']); ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="forms-input"><label>Email</label><input type="email" name="email" maxlength="100"></div>
                    <div class="forms-input"><label>Address</label><input type="text" name="address" maxlength="200"></div>
                    <div style="display:flex; gap:10px; margin-top:10px;">
                        <div class="forms-input" style="flex:1;"><label>Contact No.</label><input type="text" name="contact" maxlength="13"></div>
                        <div class="forms-input" style="flex:1;">
                            <label>Sex</label>
                            <select name="sex" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                                <option value="">Select</option><option value="Male">Male</option><option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="forms-input" style="flex:1;"><label>DOB</label><input type="date" name="dob"></div>
                    </div>
                    <div class="consultation-modal-actions">
                        <button type="submit" class="action">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="viewStaffModal" class="modal" hidden>
        <div class="modal-content">
            <div class="close-btn-div">
                <h3>Staff Profile</h3>
                <button type="button" class="close-modal-btn close-btn-patient"><img class='btn-img' src="./img/close.svg"></button>
            </div>
            <div class="modal-message">
                <div class="staff-profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar"><i class="fa-solid fa-user-doctor"></i></div>
                        <div class="profile-title">
                            <h2 id="view-name">Doctor Name</h2>
                            <span class="badge" id="view-specialty">Specialty</span>
                        </div>
                    </div>
                    <div class="profile-grid">
                        <div class="detail-item"><span class="detail-label"><i class="fa-solid fa-envelope"></i> Email</span><span class="detail-value" id="view-email"></span></div>
                        <div class="detail-item"><span class="detail-label"><i class="fa-solid fa-phone"></i> Contact</span><span class="detail-value" id="view-contact"></span></div>
                        <div class="detail-item"><span class="detail-label"><i class="fa-solid fa-venus-mars"></i> Sex</span><span class="detail-value" id="view-sex"></span></div>
                        <div class="detail-item"><span class="detail-label"><i class="fa-solid fa-cake-candles"></i> Birthday</span><span class="detail-value" id="view-dob"></span></div>
                        <div class="detail-item"><span class="detail-label"><i class="fa-solid fa-location-dot"></i> Address</span><span class="detail-value" id="view-address"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editStaffModal" class="modal" hidden>
        <div class="modal-content">
            <div class="close-btn-div">
                <h3>Edit Staff</h3>
                <button type="button" class="close-modal-btn close-btn-patient"><img class='btn-img' src="./img/close.svg"></button>
            </div>
            <div class="modal-message">
                <form action="staff_update.php" method="POST" id="editStaffForm">
                    <input type="hidden" name="id" id="edit-id">
                    <div style="display:flex; gap:10px; margin-bottom:10px;">
                        <div class="forms-input" style="flex:1;"><label>First Name</label><input type="text" name="firstname" id="edit-firstname" maxlength="50" required></div>
                        <div class="forms-input" style="flex:1;"><label>Last Name</label><input type="text" name="lastname" id="edit-lastname" maxlength="50" required></div>
                        <div class="forms-input" style="width:50px;"><label>M.I.</label><input type="text" name="middleinit" id="edit-middle" maxlength="1" size="3"></div>
                    </div>
                    <div class="forms-input">
                        <label>Specialties</label>
                        <div class="checkbox-container" id="edit-specialty-container">
                            <?php foreach($specialties as $spec): ?>
                                <label class="checkbox-item"><input type="checkbox" name="specialtyids[]" value="<?php echo $spec['SpecialtyID']; ?>"> <?php echo htmlspecialchars($spec['SpecialtyName']); ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="forms-input"><label>Email</label><input type="email" name="email" id="edit-email" maxlength="100"></div>
                    <div class="forms-input"><label>Address</label><input type="text" name="address" id="edit-address" maxlength="200"></div>
                    <div style="display:flex; gap:10px; margin-top:10px;">
                        <div class="forms-input" style="flex:1;"><label>Contact</label><input type="text" name="contact" id="edit-contact" maxlength="13"></div>
                        <div class="forms-input" style="flex:1;">
                            <label>Sex</label>
                            <select name="sex" id="edit-sex" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                                <option value="">Select</option><option value="Male">Male</option><option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="forms-input" style="flex:1;"><label>DOB</label><input type="date" name="dob" id="edit-dob"></div>
                    </div>
                    <div class="consultation-modal-actions">
                        <button type="submit" class="action">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php if ($stmt) $stmt->close(); $conn->close(); ?>
    <script src="./staff.js?v=<?php echo time(); ?>"></script>
</body>
</html>