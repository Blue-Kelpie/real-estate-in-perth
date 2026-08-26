import '@tailwindplus/elements';
import { store } from '@wordpress/interactivity';

type ServerState = {
	state: {
		isOpen: boolean;
	};
};

const storeDef = {
	state: {},
	actions: {},
	callbacks: {
		onOpen: () => {
			state.isOpen = true;
		},
		onClose: () => {
			state.isOpen = false;
		},
	},
};

type Store = ServerState & typeof storeDef;

const { state } = store<Store>('bluekelpie/drawer', storeDef);
