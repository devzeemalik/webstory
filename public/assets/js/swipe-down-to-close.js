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


import { cards, lightboxEl, player } from "./globals.js";
import { easeOutQuad, lerp } from "./utils/math.js";

let scaleVal = 1;
let scalingDown = false;
const toggleThresholdPx = 60;
let deltaY = 0;
let isSwipeY = null;
let touchStartX = 0;
let touchStartY = 0;

/**
 * Initializes listeners.
 */
 export function initializeTouchListeners() {
  player.addEventListener('amp-story-player-touchstart', (event) => {
    onTouchStart(event);
  });

  player.addEventListener('amp-story-player-touchmove', (event) => {
    onTouchMove(event);
  });

  player.addEventListener('amp-story-player-touchend', (event) => {
    onTouchEnd(event);
  });
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

/**
 * Handles onTouchStart events.
 * @param {!Event} event
 */
 function onTouchStart(event) {
  lightboxEl.classList.add('dragging');
  touchStartX = event.detail.touches[0].screenX;
  touchStartY = event.detail.touches[0].screenY;
}

/**
 * Handles onTouchMove events.
 * @param {!Event} event
 */
function onTouchMove(event) {
  const {screenX, screenY} = event.detail.touches[0];

  if (isSwipeY === null) {
    isSwipeY =
      Math.abs(touchStartY - screenY) > Math.abs(touchStartX - screenX);
  }

  if (isSwipeY === false) {
    return;
  }

  deltaY = touchStartY - screenY;

  if (deltaY > 0) {
    // Swiping up.
    return;
  }

  if (!scalingDown) {
    // Set flag so loop doesn't kick off again while it's running.
    scalingDown = true;
    // Start of animate scale.
    animateScale(0);
  }

  isSwipeY = true;
  lightboxEl.style.transform = `translate3d(0, ${Math.pow(
    -deltaY,
    0.6
  )}px, 0) scale3d(${scaleVal}, ${scaleVal}, 1)`;
}

/**
 * Handles onTouchEnd events.
 */
function onTouchEnd() {
  resetAnimationScale();

  lightboxEl.classList.remove('dragging');
  if (isSwipeY === true && Math.abs(deltaY) > toggleThresholdPx) {
    closePlayer();
  } else if (isSwipeY === true) {
    resetStyles();
  }
  isSwipeY = null;
}

/**
 * Animates scale for swipe down.
 * @param {number} val
 */
function animateScale(val) {
  if (val < 1 && scalingDown) {
    scaleVal = lerp(easeOutQuad(val), 1, 0.95);
    lightboxEl.style.transform = `translate3d(0px, ${Math.pow(
      -deltaY,
      0.6
    )}px, 0) scale3d(${scaleVal}, ${scaleVal}, 1)`;
    requestAnimationFrame(() => animateScale((val += 0.05)));
  }
}

/**
 * Resets animation scale.
 */
function resetAnimationScale() {
  scalingDown = false;
  scaleVal = 1;
}

/**
 * Resets styles of the lightbox animation.
 */
export function resetStyles() {
  lightboxEl.style.transform = `translate3d(0, 0, 0) scale3d(1, 1, 1)`;
}
