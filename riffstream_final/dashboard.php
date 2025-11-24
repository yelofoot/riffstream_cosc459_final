<?php
require __DIR__ . '/common.php';
require __DIR__ . '/db.php';
require_login();

$stmt = $pdo->prepare('SELECT user_id, first_name, last_name, username, email, account_type, created_at
                       FROM users WHERE user_id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$me = $stmt->fetch();

if (!$me) {
    $me = [
        'user_id'      => $_SESSION['user_id'],
        'first_name'   => null,
        'last_name'    => null,
        'username'     => $_SESSION['username'] ?? 'user',
        'email'        => $_SESSION['email'] ?? '',
        'account_type' => $_SESSION['account_type'] ?? 'Listener',
        'created_at'   => ''
    ];
}

$isArtist    = (isset($me['account_type']) && $me['account_type'] === 'Artist');
$displayName = trim(($me['first_name'] ?? '') . ' ' . ($me['last_name'] ?? ''));
if ($displayName === '') {
    $displayName = $me['username'];
}

$flash_msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RiffStream · Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .hero {
      padding: 20px 22px;
      border-radius: 18px;
      background:
        radial-gradient(circle at top left, rgba(94,183,255,0.38), transparent 55%),
        radial-gradient(circle at bottom right, rgba(130,255,173,0.25), transparent 55%),
        rgba(0,0,0,0.45);
      border: 1px solid rgba(255,255,255,0.18);
      margin-bottom: 22px;
      display: grid;
      grid-template-columns: minmax(0, 2fr) minmax(0, 1.1fr);
      gap: 18px;
      align-items: center;
    }
    @media (max-width: 720px) {
      .hero {
        grid-template-columns: minmax(0, 1fr);
      }
    }
    .hero-main .pill {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 999px;
      border: 1px solid rgba(255,255,255,0.18);
      font-size: 12px;
      margin-bottom: 8px;
    }
    .hero-title {
      font-size: 24px;
      margin: 0 0 6px 0;
    }
    .hero-sub {
      margin: 0;
      font-size: 14px;
      color: #9fb0ca;
    }

    .hero-art {
      justify-self: flex-end;
      max-width: 210px;
      width: 100%;
      padding: 14px;
      border-radius: 16px;
      background: linear-gradient(145deg, rgba(10,20,40,0.95), rgba(11,16,28,0.98));
      border: 1px solid rgba(255,255,255,0.22);
      box-shadow: 0 14px 38px rgba(0,0,0,0.6);
      display: grid;
      grid-template-rows: auto auto;
      gap: 10px;
    }
    .hero-art-logo-wrap {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .hero-art-logo {
      width: 40px;
      height: 40px;
      border-radius: 14px;
      background: rgba(7,12,26,0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(255,255,255,0.18);
    }
    .hero-art-logo img {
      width: 26px;
      height: 26px;
    }
    .hero-art-text {
      font-size: 13px;
      color: #d9e4ff;
    }
    .hero-wave {
      height: 40px;
      border-radius: 999px;
      background:
        linear-gradient(90deg, rgba(94,183,255,0.85), rgba(130,255,173,0.95));
      position: relative;
      overflow: hidden;
    }
    .hero-wave::before {
      content: "";
      position: absolute;
      inset: 9px;
      border-radius: 999px;
      background-image:
        repeating-linear-gradient(90deg,
          rgba(11,17,30,0.75) 0 4px,
          transparent 4px 8px);
      opacity: 0.5;
    }

    .cards {
      display: grid;
      grid-template-columns: minmax(0, 1.35fr) minmax(0, 1fr);
      gap: 20px;
      margin-top: 14px;
    }
    @media (max-width: 900px) {
      .cards {
        grid-template-columns: minmax(0, 1fr);
      }
    }
    .card-sm {
      padding: 18px 18px 20px;
      border-radius: 14px;
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.08);
    }
    .section-title {
      margin: 0 0 6px 0;
      font-size: 18px;
    }
    .muted { color: #9fb0ca; font-size: 14px; }

    .kv {
      display: grid;
      grid-template-columns: 140px 1fr;
      gap: 6px 12px;
      margin-top: 12px;
    }
    .kv div {
      padding: 2px 0;
      font-size: 14px;
    }

    .quick-actions {
      display: grid;
      grid-template-columns: 1fr;
      gap: 10px;
      margin-top: 12px;
      font-size: 14px;
    }
    .qa-item-title {
      font-weight: 500;
    }
    .qa-item-note {
      font-size: 13px;
      color: #9fb0ca;
    }

    .row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 16px;
    }
    .spacer { height: 8px; }
  </style>
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <?php if ($flash_msg): ?>
        <div class="success"><?php echo h($flash_msg); ?></div>
      <?php endif; ?>

      <div class="hero">
        <div class="hero-main">
          <span class="pill"><?php echo h($me['account_type']); ?> account</span>
          <h1 class="hero-title">
            <?php if ($isArtist): ?>
              Welcome back, <?php echo h($displayName); ?> — RiffStream is ready for your next track.
            <?php else: ?>
              Welcome back, <?php echo h($displayName); ?> — time to discover something new.
            <?php endif; ?>
          </h1>
          <p class="hero-sub">
            <?php if ($isArtist): ?>
              This is your home base for managing your artist presence and connecting with listeners.
            <?php else: ?>
              This is your home base for keeping playlists fresh and following the artists you love.
            <?php endif; ?>
          </p>
        </div>

        <aside class="hero-art" aria-hidden="true">
          <div class="hero-art-logo-wrap">
            <div class="hero-art-logo">
              <img src="images/logo.svg" alt="">
            </div>
            <div class="hero-art-text">
              <strong>RiffStream Sessions</strong><br>
              Live where your ideas turn into playlists.
            </div>
          </div>
          <div class="hero-wave"></div>
        </aside>
      </div>

      <section class="cards">
        <section class="card-sm" aria-label="Profile summary">
          <h2 class="section-title">Your profile</h2>
          <p class="muted">Basic details from your RiffStream account.</p>
          <div class="kv">
            <div class="muted">Name</div>
            <div><?php echo h($displayName ?: '—'); ?></div>

            <div class="muted">Username</div>
            <div><?php echo h($me['username']); ?></div>

            <div class="muted">Email</div>
            <div><?php echo h($me['email']); ?></div>

            <div class="muted">Joined</div>
            <div><?php echo h($me['created_at'] ?: '—'); ?></div>

            <div class="muted">Last login</div>
            <div><?php echo h($_SESSION['last_login_at'] ?? '—'); ?></div>
          </div>

          <div class="row">
            <a class="btn" href="logout.php">Log out</a>
          </div>
        </section>

        <section class="card-sm" aria-label="Experience focus">
          <?php if ($isArtist): ?>
            <h2 class="section-title">Artist spotlight</h2>
            <p class="muted">
              Think of this page as your backstage area. As RiffStream grows, this is where you’ll manage uploads and connect with fans.
            </p>
            <div class="quick-actions">
              <div>
                <div class="qa-item-title">Upload and organize tracks</div>
                <div class="qa-item-note">Plan EPs, singles, and albums so listeners can explore your catalog.</div>
              </div>
              <div>
                <div class="qa-item-title">Shape your sound identity</div>
                <div class="qa-item-note">Use consistent genres and descriptions so your music is easier to find.</div>
              </div>
              <div>
                <div class="qa-item-title">Grow your audience</div>
                <div class="qa-item-note">Encourage follows and build playlists that show off your best work.</div>
              </div>
            </div>
          <?php else: ?>
            <h2 class="section-title">Listener hub</h2>
            <p class="muted">
              As RiffStream grows, this page will evolve into your personal music control center.
            </p>
            <div class="quick-actions">
              <div>
                <div class="qa-item-title">Create and tune playlists</div>
                <div class="qa-item-note">Group songs by mood, activity, or genre and refine them over time.</div>
              </div>
              <div>
                <div class="qa-item-title">Follow favorite artists</div>
                <div class="qa-item-note">Keep up with new releases and curated collections.</div>
              </div>
              <div>
                <div class="qa-item-title">Explore new sounds</div>
                <div class="qa-item-note">Use genres and tags to jump into new “sound worlds.”</div>
              </div>
            </div>
          <?php endif; ?>

          <div class="spacer"></div>
          <p class="muted">
            For this course project, this dashboard mainly proves that:
          </p>
          <ul class="muted">
            <li>Sign-up and login are working and protected by sessions.</li>
            <li>User details are stored in the <code>users</code> table and displayed here.</li>
            <li>The experience adapts a bit for Artists vs Listeners.</li>
          </ul>
        </section>
      </section>

      <div class="footer">RiffStream · dashboard/homepage for COSC 459</div>
    </main>
  </div>
</body>
</html>

