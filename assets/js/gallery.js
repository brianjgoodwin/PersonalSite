/**
 * Horizontal Scroll Gallery with Modal Viewer
 * Lightweight gallery component for app screenshots
 * Features: Click to expand, keyboard navigation, accessibility
 */

(function() {
  'use strict';

  // Wait for DOM to be ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGallery);
  } else {
    initGallery();
  }

  function initGallery() {
    const galleries = document.querySelectorAll('.gallery');

    galleries.forEach(gallery => {
      const scrollContainer = gallery.querySelector('.gallery-scroll');
      if (!scrollContainer) return;

      const items = Array.from(scrollContainer.querySelectorAll('.gallery-item'));
      if (items.length === 0) return;

      // Create modal
      const modal = createModal();
      document.body.appendChild(modal);

      // Setup click handlers for each gallery item
      items.forEach((item, index) => {
        item.addEventListener('click', () => openModal(items, index, modal));
        item.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            openModal(items, index, modal);
          }
        });
      });
    });
  }

  function createModal() {
    const modal = document.createElement('div');
    modal.className = 'gallery-modal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-hidden', 'true');
    modal.setAttribute('aria-label', 'Image viewer');

    modal.innerHTML = `
      <div class="gallery-modal-content">
        <button class="gallery-close" aria-label="Close image viewer" type="button">
          ×
        </button>
        <button class="gallery-prev" aria-label="Previous image" type="button">
          ‹
        </button>
        <button class="gallery-next" aria-label="Next image" type="button">
          ›
        </button>
        <img src="" alt="">
        <div class="gallery-modal-caption"></div>
        <div class="sr-only" aria-live="polite" aria-atomic="true"></div>
      </div>
    `;

    return modal;
  }

  function openModal(items, startIndex, modal) {
    let currentIndex = startIndex;
    const modalImg = modal.querySelector('img');
    const modalCaption = modal.querySelector('.gallery-modal-caption');
    const liveRegion = modal.querySelector('[aria-live]');
    const closeBtn = modal.querySelector('.gallery-close');
    const prevBtn = modal.querySelector('.gallery-prev');
    const nextBtn = modal.querySelector('.gallery-next');

    // Store the element that opened the modal for focus restoration
    const triggerElement = items[currentIndex];

    // Get all focusable elements in modal for focus trap
    const focusableElements = modal.querySelectorAll(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];

    // Show modal
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling

    // Load initial image
    updateImage();

    // Event listeners
    closeBtn.addEventListener('click', closeModal);
    prevBtn.addEventListener('click', showPrev);
    nextBtn.addEventListener('click', showNext);
    modal.addEventListener('click', handleBackdropClick);
    document.addEventListener('keydown', handleKeydown);

    // Focus the close button for keyboard users
    closeBtn.focus();

    function updateImage() {
      const item = items[currentIndex];
      const img = item.querySelector('img');

      modalImg.src = img.src;
      modalImg.alt = img.alt || '';

      // Update caption if exists
      const caption = img.getAttribute('data-caption');
      if (caption) {
        modalCaption.textContent = caption;
        modalCaption.style.display = 'block';
      } else {
        modalCaption.style.display = 'none';
      }

      // Update button states
      prevBtn.disabled = currentIndex === 0;
      nextBtn.disabled = currentIndex === items.length - 1;

      // Announce image position to screen readers
      if (liveRegion) {
        const announcement = `Image ${currentIndex + 1} of ${items.length}`;
        liveRegion.textContent = announcement;
      }
    }

    function showPrev() {
      if (currentIndex > 0) {
        currentIndex--;
        updateImage();
      }
    }

    function showNext() {
      if (currentIndex < items.length - 1) {
        currentIndex++;
        updateImage();
      }
    }

    function closeModal() {
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = ''; // Restore scrolling

      // Remove event listeners
      closeBtn.removeEventListener('click', closeModal);
      prevBtn.removeEventListener('click', showPrev);
      nextBtn.removeEventListener('click', showNext);
      modal.removeEventListener('click', handleBackdropClick);
      document.removeEventListener('keydown', handleKeydown);

      // Restore focus to the thumbnail that opened the modal
      triggerElement.focus();
    }

    function handleBackdropClick(e) {
      // Close if clicking the backdrop (not the content)
      if (e.target === modal) {
        closeModal();
      }
    }

    function handleKeydown(e) {
      // Focus trap
      if (e.key === 'Tab') {
        if (e.shiftKey) {
          // Shift + Tab
          if (document.activeElement === firstFocusable) {
            lastFocusable.focus();
            e.preventDefault();
          }
        } else {
          // Tab
          if (document.activeElement === lastFocusable) {
            firstFocusable.focus();
            e.preventDefault();
          }
        }
      }

      // Navigation
      switch(e.key) {
        case 'Escape':
          closeModal();
          break;
        case 'ArrowLeft':
          showPrev();
          break;
        case 'ArrowRight':
          showNext();
          break;
      }
    }
  }
})();
