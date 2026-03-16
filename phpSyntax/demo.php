<?php
declare(strict_types=1);

// Keep a lightweight user context (last submitted name) across page reloads.
session_start();

$errors = [];
$resultMessage = null;

// Reusable helper for consistent money display in the products table.
function formatCurrency(float $amount): string
{
    return '$' . number_format($amount, 2);
}

// Business helper: safely compute average and avoid division by zero.
function calculateAverage(array $numbers): float
{
    if (count($numbers) === 0) {
        return 0;
    }

    return array_sum($numbers) / count($numbers);
}

class Student
{
    public function __construct(
        public string $name,
        public array $scores
    ) {
    }

    public function average(): float
    {
        return calculateAverage($this->scores);
    }
}

// Example dataset to demonstrate arrays + loops + conditionals in the UI.
$products = [
    ['name' => 'Keyboard', 'price' => 49.99, 'stock' => 12],
    ['name' => 'Mouse', 'price' => 29.5, 'stock' => 0],
    ['name' => 'Monitor', 'price' => 199.0, 'stock' => 6],
];

$student = new Student('Muhammed', [95, 88, 92, 90]);

// Handle form submission, validate inputs, parse numbers, then compute average.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $numbersInput = trim($_POST['numbers'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if ($numbersInput === '') {
        $errors[] = 'Please enter numbers separated by commas.';
    }

    $numbers = array_filter(array_map('trim', explode(',', $numbersInput)), static fn($value) => $value !== '');

    $parsedNumbers = [];
    foreach ($numbers as $value) {
        if (!is_numeric($value)) {
            $errors[] = "Invalid number: {$value}";
            continue;
        }
        $parsedNumbers[] = (float)$value;
    }

    if (count($errors) === 0) {
        $average = calculateAverage($parsedNumbers);
        $_SESSION['last_user'] = $name;
        $resultMessage = "Hi {$name}, average = " . number_format($average, 2);
    }
}

$lastUser = $_SESSION['last_user'] ?? 'Guest';
$today = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Essentials Website</title>
  <link rel="stylesheet" href="styles.css">

</head>

<body>
  <div class="container">
    <div class="card">
      <h1>PHP Syntax Website</h1>
      <p class="muted">A combined demo of variables, arrays, functions, conditionals, loops, forms, sessions, and OOP.
      </p>
      <p><strong>Current time:</strong> <?= htmlspecialchars($today) ?></p>
      <p><strong>Last visitor (session):</strong> <?= htmlspecialchars($lastUser) ?></p>
    </div>

    <div class="card">
      <h2>1) Arrays + Loops + Conditionals</h2>
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <!-- Render products dynamically from the PHP array above. -->
          <?php foreach ($products as $product): ?>
          <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= formatCurrency((float)$product['price']) ?></td>
            <td>
              <?php if ($product['stock'] > 0): ?>
              <span class="ok">In Stock (<?= (int)$product['stock'] ?>)</span>
              <?php else: ?>
              <span class="err">Out of Stock</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="card">
      <h2>2) Function + Form Handling (GET/POST style)</h2>
      <form method="post" action="">
        <label for="name">Your Name</label>
        <input id="name" name="name" type="text" placeholder="Example: Ali"
          value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

        <label for="numbers">Numbers (comma separated)</label>
        <input id="numbers" name="numbers" type="text" placeholder="10, 20, 30"
          value="<?= htmlspecialchars($_POST['numbers'] ?? '') ?>">

        <button type="submit">Calculate Average</button>
      </form>

      <?php if (count($errors) > 0): ?>
      <ul class="err">
        <?php foreach ($errors as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
      <?php elseif ($resultMessage !== null): ?>
      <p class="ok"><?= htmlspecialchars($resultMessage) ?></p>
      <?php else: ?>
      <p class="muted">Submit the form to run PHP validation and function logic.</p>
      <?php endif; ?>
    </div>

    <div class="card">
      <h2>3) OOP (Class Example)</h2>
      <p>Student: <strong><?= htmlspecialchars($student->name) ?></strong></p>
      <p>Scores:
        <code><?= htmlspecialchars(implode(', ', array_map(static fn($score) => (string)$score, $student->scores))) ?></code>
      </p>
      <p>Average from class method: <strong><?= number_format($student->average(), 2) ?></strong></p>
    </div>
  </div>
</body>

</html>
