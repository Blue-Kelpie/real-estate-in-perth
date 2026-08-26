import { store } from '@wordpress/interactivity';

type ServerState = {
	state: {};
};

const storeDef = {
	state: {},
	actions: {},
	callbacks: {},
};

type Store = ServerState & typeof storeDef;

const { state } = store<Store>('bluekelpie/example-block', storeDef);
