<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Teacher's Dashboard</h2>
        <input type="text" id="nameSearch" class="form-control w-25" placeholder="Search by name..." onkeyup="filterTable()">
    </div>

    <table class="table table-hover table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Teacher's Name</th>
                <th>Email</th>
                <th>Grade</th>
                <th>Class</th>
                <th>Teachers</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr class="user-row">
                <td class="full-name"><?= htmlspecialchars($user->get_first_name() . " " . $user->get_last_name()) ?></td>
                <td><?= htmlspecialchars($user->get_email()) ?></td>
                <td><?= htmlspecialchars($user->get_grade() ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($user->get_class() ?? 'N/A') ?></td>
                <td><span class="badge badge-info"><?= htmlspecialchars($user->get_role()) ?></span></td>
                <td>
                    <?php if ($_SESSION['user_id'] == $user->get_id() || $_SESSION['user_role'] == 'superuser'): ?>
                        <a href="/teachers/change-password?id=<?= $user->get_id() ?>" class="btn btn-sm btn-warning">Change Password</a>
                    <?php endif; ?>

                    <?php if ($_SESSION['user_role'] == 'superuser' && $_SESSION['user_id'] != $user->get_id()): ?>
                        <form method="post" action="/teachers/delete-user" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user->get_id() ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function filterTable() {
    let input = document.getElementById('nameSearch').value.toLowerCase();
    let rows = document.querySelectorAll('.user-row');
    rows.forEach(row => {
        let name = row.querySelector('.full-name').textContent.toLowerCase();
        row.style.display = name.includes(input) ? "" : "none";
    });
}
</script>
<td>
    <?php if ($_SESSION['user_id'] == $user->get_id() || $_SESSION['user_role'] == 'superuser'): ?>
        <a href="/teachers/change-password?id=<?= $user->get_id() ?>" 
    <?php endif; ?>

    <?php if ($_SESSION['user_role'] == 'superuser' && $_SESSION['user_id'] != $user->get_id()): ?>
        <form method="post" action="/teachers/delete-user" style="display:inline;">
            <input type="hidden" name="user_id" value="<?= $user->get_id() ?>">
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this account?')">Delete</button>
        </form>
    <?php endif; ?>
</td>