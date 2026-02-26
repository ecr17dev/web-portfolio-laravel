#!/bin/sh
# Generate empty Wayfinder stubs so Vite can build without Laravel backend.
# These are replaced at runtime by `php artisan wayfinder:generate`.
set -e

ROUTES_DIR="resources/js/routes"
ACTIONS_DIR="resources/js/actions/App/Http/Controllers/Settings"

stub() { echo "export const $1 = (...args) => ({ url: '#', method: 'GET' });"; }

mkdir -p "$ROUTES_DIR/two-factor" "$ROUTES_DIR/password" "$ACTIONS_DIR"

# @/routes (index)
cat > "$ROUTES_DIR/index.ts" <<'EOF'
export const login = (...a: any[]) => ({ url: '#', method: 'GET' });
export const logout = (...a: any[]) => ({ url: '#', method: 'POST' });
export const home = (...a: any[]) => ({ url: '#', method: 'GET' });
export const dashboard = (...a: any[]) => ({ url: '#', method: 'GET' });
EOF

# @/routes/appearance
cat > "$ROUTES_DIR/appearance.ts" <<'EOF'
export const edit = (...a: any[]) => ({ url: '#', method: 'GET' });
EOF

# @/routes/profile
cat > "$ROUTES_DIR/profile.ts" <<'EOF'
export const edit = (...a: any[]) => ({ url: '#', method: 'GET' });
EOF

# @/routes/user-password
cat > "$ROUTES_DIR/user-password.ts" <<'EOF'
export const edit = (...a: any[]) => ({ url: '#', method: 'GET' });
EOF

# @/routes/two-factor
cat > "$ROUTES_DIR/two-factor/index.ts" <<'EOF'
export const show = (...a: any[]) => ({ url: '#', method: 'GET' });
export const enable = (...a: any[]) => ({ url: '#', method: 'POST' });
export const disable = (...a: any[]) => ({ url: '#', method: 'DELETE' });
export const confirm = (...a: any[]) => ({ url: '#', method: 'POST' });
export const qrCode = (...a: any[]) => ({ url: '#', method: 'GET' });
export const recoveryCodes = (...a: any[]) => ({ url: '#', method: 'GET' });
export const secretKey = (...a: any[]) => ({ url: '#', method: 'GET' });
export const regenerateRecoveryCodes = (...a: any[]) => ({ url: '#', method: 'POST' });
EOF

# @/routes/two-factor/login
cat > "$ROUTES_DIR/two-factor/login.ts" <<'EOF'
export const store = (...a: any[]) => ({ url: '#', method: 'POST' });
EOF

# @/routes/login
cat > "$ROUTES_DIR/login.ts" <<'EOF'
export const store = (...a: any[]) => ({ url: '#', method: 'POST' });
EOF

# @/routes/register
cat > "$ROUTES_DIR/register.ts" <<'EOF'
export const store = (...a: any[]) => ({ url: '#', method: 'POST' });
EOF

# @/routes/password
cat > "$ROUTES_DIR/password.ts" <<'EOF'
export const request = (...a: any[]) => ({ url: '#', method: 'POST' });
export const email = (...a: any[]) => ({ url: '#', method: 'POST' });
export const update = (...a: any[]) => ({ url: '#', method: 'POST' });
EOF

# @/routes/password/confirm
cat > "$ROUTES_DIR/password/confirm.ts" <<'EOF'
export const store = (...a: any[]) => ({ url: '#', method: 'POST' });
EOF

# @/routes/verification
cat > "$ROUTES_DIR/verification.ts" <<'EOF'
export const send = (...a: any[]) => ({ url: '#', method: 'POST' });
EOF

# @/actions/App/Http/Controllers/Settings/ProfileController
cat > "$ACTIONS_DIR/ProfileController.ts" <<'EOF'
const ProfileController = { update: (...a: any[]) => ({ url: '#', method: 'PUT' }), destroy: (...a: any[]) => ({ url: '#', method: 'DELETE' }) };
export default ProfileController;
EOF

# @/actions/App/Http/Controllers/Settings/PasswordController
cat > "$ACTIONS_DIR/PasswordController.ts" <<'EOF'
const PasswordController = { update: (...a: any[]) => ({ url: '#', method: 'PUT' }) };
export default PasswordController;
EOF

echo "✅ Wayfinder stubs generated"
