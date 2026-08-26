/**
 * Ambient global types, shared across the theme's TypeScript files.
 */

type JQueryCollection = {
	on: (
		events: string,
		handler: (event: Event, ...extraParams: unknown[]) => void
	) => JQueryCollection;
};

type JQueryStatic = (target: Document | Element | string) => JQueryCollection;

type GformSubmissionStartedData = {
	form: HTMLFormElement;
	submissionType: string;
	submissionMethod: string;
};

type Gform = {
	initializeOnLoaded: (callback: () => void) => void;
	utils: {
		addFilter: (
			event: string,
			callback: (
				data: GformSubmissionStartedData
			) => GformSubmissionStartedData,
			priority?: number
		) => void;
	};
};

declare const gform: Gform;
declare const jQuery: JQueryStatic;

interface Window {
	gform: Gform;
	jQuery: JQueryStatic;
}
