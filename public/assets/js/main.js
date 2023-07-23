/**
 * Copyright 2021 The AMP HTML Authors. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS-IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

import { initializeCards } from "./cards.js";
import { initializeArrows } from "./scrolling-arrows.js";
import { cards, lightboxEl, player, setLightbox, setPlayer } from "./globals.js";
import { initializeTouchListeners } from "./swipe-down-to-close.js";

window.addEventListener('load', function () {
  init();
});

/**
 * Initializes the carousel once the player is ready.
 */
function init() {
  const playerEl = document.body.querySelector('amp-story-player');
  setPlayer(playerEl);

  if (player.isReady) {
    initializeCarousel();
  } else {
    player.addEventListener('ready', () => {
      initializeCarousel();
    });
  }
}

/**
 * Initializes cards, arrows, and listeners for the carousel.
 */
function initializeCarousel() {
  const lightbox = document.body.querySelector('.lightbox');
  setLightbox(lightbox);

  initializeCards();
  initializeArrows();

  player.addEventListener('amp-story-player-close', () => {
    closePlayer();
  });

  // For swipe down to close.
  initializeTouchListeners();
}

/**
 * Closes the player from the lightbox experience.
 */
 function closePlayer() {
  player.pause();
  document.body.classList.toggle('lightbox-open', false);
  lightboxEl.classList.add('closed');
  cards.forEach((card) => {
    card.classList.remove('hidden');
  });
}