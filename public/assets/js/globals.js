export let cards;
export let cardMargin;
export let cardWidth;

export let player;
export let lightboxEl;

export function setPlayer(playerEl) {
  player = playerEl;
}

export function setLightbox(lightbox) {
  lightboxEl = lightbox;
}

export function setCards(cardEls) {
  cards = cardEls;
}

export function setCardWidth(width) {
  cardWidth = width;
}

export function setCardMargin(margin) {
  cardMargin = margin;
}
