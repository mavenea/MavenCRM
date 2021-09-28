/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
  * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/
/* crmv@171524 */

window.VTE = window.VTE || {};

VTE.StompUtils = VTE.StompUtils || {
	
	client: null,
	
	connectionRetries: 0,
	
	defaults: {
		debug: false,
		heartbeat: true, //@enabled heartbeat
	},
	
	isSupported: function() {
		//@nileio return (('SockJS' in window) && ('Stomp' in window));
		return (('WebSocket' in window) && ('Stomp' in window));
	},
	
	connect: function(connectionParams, onConnect, onError, params) {
		var me = this,
			params = params || {};
			
		if (!me.isSupported()) {
			console.error('Stomp plugin not found.');
			return false;
		}
			
		if (!connectionParams) {
			console.warn('Invalid connection parameters.');
			return false;
		}
		
		function connectionCompleted() {
			me.isConnecting = false;
			me.connectionRetries = 0;
			onConnect();
		}
		
		if (me.client && me.client.connected) {
			console.log("CONNECTED!");
			connectionCompleted();
			return me.client;
		}
		
		if (me.isConnecting) {
			if (me.connectionRetries < 10) {
				setTimeout(function() {
					me.connectionRetries++;
					console.log("RETRIES: ", me.connectionRetries);
					me.connect(connectionParams, onConnect, onError, params);
				}, 100);
			}
			return false;
		}
		
		me.isConnecting = true;
		
		onConnect = onConnect || function() {};

		onError = onError || function(error) {
			console.log('stomp error: ' + error );
			me.cleanUp();
		};
		
		params = jQuery.extend({}, me.defaults, params);
		//@nileio SockJS is no longer the server used by RabbitMQ WebStomp websocket server. RabbitMQ WebStomp plugin uses cowboy http/ws server now.
		//therefore cannot use SockJS.
		//WebSocket is available on all modern browsers and RabbitMQ is a standard WebSocket server.
	//	var ws = new SockJS(connectionParams['host']);
		//var ws = new WebSocket(connectionParams['host']);
		var client = Stomp.client(connectionParams['host']);

		if (!params.debug) {
			client.debug = null;
		}
		//@nileio Bugfix: should be if true
		if (params.heartbeat) {
			client.heartbeat = {
				outgoing: 10000, //send a heartbeat every 10 seconds
				incoming: 10000 // require a heartbeat from server every 10 seconds - this will terminate at the reverse proxy
			};
		}
		
		client.connect(connectionParams['user'], connectionParams['password'], connectionCompleted, onError, connectionParams['virtual_host']);

		me.client = client;
		
		return client;
	},
	
	checkClient: function() {
		var me = this;
		
		if (!me.client) {
			console.warn('Stomp client is not initialized.');
			return false;
		}
		
		if (!me.client.connected) {
			console.warn('Stomp client is not connected.');
			return false;
		}
		
		return true;
	},
	
	subscribe: function(destination, callback) {
		var me = this,
			callback = callback || function() {};
		
		if (!me.checkClient()) {
			return false;
		}
		
		var subscribtion = me.client.subscribe(destination, callback);
		
		return subscribtion;
	},
	
	subscribeToTopic: function(topicName, callback) {
		var me = this;
		
		if (!me.checkClient()) {
			return false;
		}
		
		var destination = "/topic/" + topicName;
		var subscribtion = me.subscribe(destination, callback);
		
		return subscribtion;
	},
	
	unsubscribe: function(subscribtion) {
		var me = this;
		
		if (!me.checkClient()) {
			return false;
		}
		
		return me.client.unsubscribe(subscribtion);
	},
	
	disconnect: function() {
		var me = this;
		
		if (!me.checkClient()) {
			return false;
		}
		
		me.client.disconnect();
	},
	
	cleanUp: function() {
		this.disconnect();
		this.client = null;
	},
	
};