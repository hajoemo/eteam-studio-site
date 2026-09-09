<?php global $Wcms ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Encoding, browser compatibility, viewport -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Search Engine Optimization (SEO) -->
	<meta name="title" content="<?= $Wcms->get('config', 'siteTitle') ?> - <?= $Wcms->page('title') ?>" />
	<meta name="description" content="<?= $Wcms->page('description') ?>">
	<meta name="keywords" content="<?= $Wcms->page('keywords') ?>">
	<meta property="og:url" content="<?= $Wcms->getCurrentPageUrl() ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="<?= $Wcms->get('config', 'siteTitle') ?>" />
	<meta property="og:title" content="<?= $Wcms->page('title') ?>" />
	<meta name="twitter:title" content="<?= $Wcms->get('config', 'siteTitle') ?> - <?= $Wcms->page('title') ?>" />
	<meta name="twitter:description" content="<?= $Wcms->page('description') ?>" />

	<!-- Website and page title -->
	<title><?= $Wcms->get('config', 'siteTitle') ?> - <?= $Wcms->page('title') ?></title>

	<!-- Theme fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

	<!-- Admin CSS -->
	<?= $Wcms->css() ?>

	<!-- Theme CSS -->
	<link rel="stylesheet" rel="preload" as="style" href="<?= $Wcms->asset('css/style.css') ?>">
</head>
<body>

	<!-- Admin settings panel and alerts -->
	<?= $Wcms->settings() ?>
	<?= $Wcms->alerts() ?>

	<header>
		<a href="<?= $Wcms->url() ?>" class="mark"><?= $Wcms->siteTitle() ?></a>
		<nav id="nav">
			<ul class="menu">
				<!-- WonderCMS page menu -->
				<?= $Wcms->menu() ?>
			</ul>
		</nav>
	</header>

	<!-- Main editable area: current page content (hero, section nav, galleries, summaries) -->
	<?= $Wcms->page('content') ?>

	<!-- Static editable area, visible on every page (e.g. a short site-wide note) -->
	<?php if (trim(strip_tags($Wcms->block('subside'))) !== ''): ?>
	<div class="subside">
		<?= $Wcms->block('subside') ?>
	</div>
	<?php endif; ?>

	<footer>
		<span><?= $Wcms->footer() ?></span>
		<a href="#top" class="top-link">Back to top ↑</a>
	</footer>

	<div class="lightbox" id="lightbox" aria-hidden="true">
		<button class="lightbox-close" id="lightbox-close" aria-label="Close">✕</button>
		<div class="lightbox-inner" id="lightbox-inner"></div>
		<div class="lightbox-caption" id="lightbox-caption"></div>
	</div>

	<script>
		// Scrollspy: highlights the active in-page section link as sections pass under the header
		document.addEventListener('DOMContentLoaded', function () {
			const sections = document.querySelectorAll('section.gallery[id]');
			const links = document.querySelectorAll('.section-nav a');

			const setActive = (id) => {
				links.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + id));
			};

			if (sections.length && links.length && 'IntersectionObserver' in window) {
				const observer = new IntersectionObserver((entries) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) setActive(entry.target.id);
					});
				}, { rootMargin: '-40% 0px -55% 0px', threshold: 0 });

				sections.forEach(s => observer.observe(s));
			}

			// ---------- Lightbox ----------
			// A tile only becomes clickable once it actually contains an <img>, <video>, or <iframe>.
			const lightbox = document.getElementById('lightbox');
			const lightboxInner = document.getElementById('lightbox-inner');
			const lightboxCaption = document.getElementById('lightbox-caption');
			const lightboxClose = document.getElementById('lightbox-close');

			const EXPAND_ICON = `
				<svg viewBox="0 0 24 24" fill="none" stroke="#F6F6F3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M9 3H3v6M15 3h6v6M21 15v6h-6M3 15v6h6"/>
				</svg>`;

			document.querySelectorAll('.tile').forEach(tile => {
				const media = tile.querySelector('img, video, iframe');
				if (!media) return; // still a placeholder — nothing to enlarge yet

				tile.classList.add('has-media');
				tile.setAttribute('tabindex', '0');
				tile.setAttribute('role', 'button');
				tile.setAttribute('aria-label', 'View larger');

				const hint = document.createElement('span');
				hint.className = 'expand-hint';
				hint.innerHTML = EXPAND_ICON;
				tile.appendChild(hint);

				const open = () => openLightbox(tile, media);
				tile.addEventListener('click', open);
				tile.addEventListener('keydown', (e) => {
					if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); }
				});
			});

			function openLightbox(tile, media) {
				lightboxInner.innerHTML = '';
				const caption = tile.querySelector('.tile-caption span');
				lightboxCaption.textContent = caption ? caption.textContent : '';

				if (media.tagName === 'IMG') {
					const img = document.createElement('img');
					img.src = media.src;
					img.alt = media.alt || '';
					lightboxInner.appendChild(img);

				} else if (media.tagName === 'VIDEO') {
					const vid = document.createElement('video');
					vid.src = media.currentSrc || media.src;
					vid.controls = true;
					vid.autoplay = true;
					vid.playsInline = true;
					lightboxInner.appendChild(vid);

				} else if (media.tagName === 'IFRAME') {
					// Rebuild the Vimeo src without background mode so the full player (with controls) shows
					const iframe = document.createElement('iframe');
					let src = media.src.replace('background=1', 'autoplay=1').replace(/[&?]autopause=0/, '');
					iframe.src = src;
					iframe.setAttribute('allow', 'autoplay; fullscreen; picture-in-picture');
					iframe.setAttribute('allowfullscreen', '');
					lightboxInner.appendChild(iframe);
				}

				lightbox.classList.add('active');
				lightbox.setAttribute('aria-hidden', 'false');
				document.body.style.overflow = 'hidden';
				lightboxClose.focus();
			}

			function closeLightbox() {
				lightbox.classList.remove('active');
				lightbox.setAttribute('aria-hidden', 'true');
				document.body.style.overflow = '';
				lightboxInner.innerHTML = ''; // stops video/embed playback
			}

			lightboxClose.addEventListener('click', closeLightbox);
			lightbox.addEventListener('click', (e) => {
				if (e.target === lightbox) closeLightbox(); // click on backdrop, not the media itself
			});
			document.addEventListener('keydown', (e) => {
				if (e.key === 'Escape' && lightbox.classList.contains('active')) closeLightbox();
			});
		});
	</script>

	<!-- Admin JavaScript, required for saving content -->
	<?= $Wcms->js() ?>

</body>
</html>
