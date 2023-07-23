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

import { cardMargin, cards, cardWidth } from "./globals.js";

let leftArrow;
let rightArrow;
let scrollX = 0;
let maxScroll;

/**
 * Initializes arrows for horizontal scrolling on desktop.
 */
export function initializeArrows() {
  const scrollContainer = document.querySelector('.carousel-cards-container');
  const containerPadding =
    parseFloat(getComputedStyle(scrollContainer.firstElementChild).paddingLeft) +
    parseFloat(getComputedStyle(scrollContainer.firstElementChild).paddingRight);

  leftArrow = document.querySelector('.entry-point-left-arrow');
  rightArrow = document.querySelector('.entry-point-right-arrow');

  maxScroll =
    scrollContainer.offsetWidth -
    containerPadding +
    cardMargin -
    cards.length * cardWidth;

  if (maxScroll >= 0) {
    return;
  }

  const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
  rightArrow.classList.toggle('visible', !isMobile);

  const carousel = document.querySelector('.carousel-container');
  carousel.classList.toggle('overflow-right', true);

  addArrowListener(leftArrow, true);
  addArrowListener(rightArrow);
}

/**
 * Adds click listeners to scrolling arrows.
 * @param {!Element} button
 * @param {boolean} isLeft
 */
function addArrowListener(button, isLeft = false) {
  const carousel = document.querySelector('.carousel-container');
  button.addEventListener('click', () => {
    scrollX = isLeft
      ? Math.min(0, scrollX + cardWidth * 2)
      : Math.max(maxScroll, scrollX - cardWidth * 2);

    cards.forEach((card) => {
      card.style['transform'] = `translateX(${scrollX}px)`;
    });

        carousel.classList.toggle('overflow-left', scrollX < 0);
    carousel.classList.toggle('overflow-right', scrollX > maxScroll);

    leftArrow.classList.toggle('visible', scrollX < 0);
    rightArrow.classList.toggle('visible', scrollX > maxScroll);
  });
}
