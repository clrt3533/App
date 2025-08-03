'use client'

import { SessionProvider } from 'next-auth/react'
import { ReactNode, Suspense } from 'react'

interface AuthProviderProps {
  children: ReactNode
}

// Loading component for auth states
function AuthLoading() {
  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50">
      <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
    </div>
  )
}

// Error boundary for auth failures
function AuthErrorBoundary({ children }: { children: ReactNode }) {
  return (
    <Suspense fallback={<AuthLoading />}>
      {children}
    </Suspense>
  )
}

export function AuthProvider({ children }: AuthProviderProps) {
  return (
    <AuthErrorBoundary>
      <SessionProvider
        // Disable refetch on window focus to prevent unnecessary requests
        refetchOnWindowFocus={false}
        // Set session max age
        refetchInterval={5 * 60} // 5 minutes
      >
        {children}
      </SessionProvider>
    </AuthErrorBoundary>
  )
}