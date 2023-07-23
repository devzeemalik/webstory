/**
 * Linear interpolation function for animation.
 * @param {number} pct
 * @param {number} v0
 * @param {number} v1
 * @return {number}
 */
export function lerp(pct, v0, v1) {
  return v0 * (1 - pct) + v1 * pct;
}

/**
 * Ease out quad for animation.
 * @param {number} t
 * @return {number}
 */
export function easeOutQuad(t) {
  return --t * t * t + 1;
}
