/**
 *
 * WordPress' domReady. It's so small there's no need for it to be a node dependency.
 *
 * @see https://www.npmjs.com/package/@wordpress/dom-ready
 * @see https://github.com/WordPress/gutenberg/blob/trunk/packages/dom-ready/src/index.ts
 * @param callback
 */
export function domReady(callback: VoidFunction) {
	if (typeof document === 'undefined') {
		return;
	}

	if (
		document.readyState === 'complete' || // DOMContentLoaded + Images/Styles/etc loaded, so we call directly.
		document.readyState === 'interactive' // DOMContentLoaded fires at this point, so we call directly.
	) {
		return void callback();
	}

	// DOMContentLoaded has not fired yet, delay callback until then.
	document.addEventListener('DOMContentLoaded', callback);
}
