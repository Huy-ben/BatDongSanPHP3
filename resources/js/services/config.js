export const API_BEARER_TOKEN = '3|tuL1MGYRQemXiKjwy1YwAwpnj9wOQhicZ3mbhi6Y08e24753';

export function getBearerAuthHeaders(token = API_BEARER_TOKEN) {
	return {
		Authorization: `Bearer ${token}`,
	};
}
