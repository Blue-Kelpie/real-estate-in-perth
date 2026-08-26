import { store } from '@wordpress/interactivity';
import { spring, animateView } from 'motion';

gform.initializeOnLoaded(function () {
	window.gform.utils.addFilter(
		'gform/submission/submission_started',
		function (data) {
			actions.submissionStarted(data);
			return data;
		}
	);
});

jQuery(document).on('gform_confirmation_loaded', function (_event, formId) {
	actions.confirmationLoaded(formId);
});

jQuery(document).on('gform_post_render', function (_event, formId) {
	actions.validationFailed(formId);
});

type Store = {
	state: {
		formContainer: HTMLElement | null;
		confirmations: Record<string, string>;
	};
};

const storeDef = {
	state: {},
	actions: {
		submissionStarted: (data: GformSubmissionStartedData) => {
			state.formContainer =
				data.form.closest<HTMLElement>('[data-slot="form"]');

			callbacks.maybeSetFormHeightProperty();
			callbacks.maybeAppendLoadingState();
		},
		confirmationLoaded: (formId: unknown) => {
			const messageContainer = state.formContainer?.querySelector(
				'.bk-gform-state-container'
			);
			if (!messageContainer) {
				return;
			}

			const message =
				state.confirmations?.[String(formId)] ?? 'Thank you!';

			const markup = `
				<div class='container'>
					<span class='check'>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
					</span>
					<p>${message}</p>
				</div>
			`;

			animateView(
				() => {
					messageContainer.innerHTML = markup;
				},
				{ type: spring, visualDuration: 0.45, bounce: 0.2 }
			).add('.bk-gform-state-container');
		},
		validationFailed: (formId: unknown) => {
			const wrapper = document.getElementById(`gform_wrapper_${formId}`);

			wrapper
				?.closest<HTMLElement>('[data-slot="form"]')
				?.querySelector('.bk-gform-state-container')
				?.remove();
		},
	},
	callbacks: {
		maybeSetFormHeightProperty: () => {
			const wrapper =
				state.formContainer?.querySelector<HTMLElement>(
					'.gform_wrapper'
				);

			if (!wrapper) {
				return;
			}

			const height = wrapper.getBoundingClientRect().height;
			document.documentElement.style.setProperty(
				'--form-height',
				`${height}px`
			);
		},
		maybeAppendLoadingState: () => {
			if (!state.formContainer) {
				return;
			}

			const confirmationMessage = `
					<div class='container'>
						<span class='loading-spinner-wrapper'>
							<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="loading-spinner"><g clip-path="url(#clip0_8124_707)"><path d="M37.9733 19.9993C37.9733 29.9258 29.9263 37.9728 19.9998 37.9728C10.0734 37.9728 2.02637 29.9258 2.02637 19.9993C2.02637 10.0729 10.0734 2.02588 19.9998 2.02588C22.2367 2.02588 24.3781 2.4345 26.3535 3.18116" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></g><defs><clipPath id="clip0_8124_707"><rect width="40" height="40" fill="white"/></clipPath></defs></svg>
						</span>
						<p class='loading'>Loading</p>
					</div>
			`;

			const messageContainer = document.createElement('div');
			messageContainer.classList.add('bk-gform-state-container');
			messageContainer.innerHTML = confirmationMessage;

			state.formContainer.appendChild(messageContainer);
		},
	},
};

const { state, actions, callbacks } = store<Store & typeof storeDef>(
	'bluekelpie/gform',
	storeDef
);
