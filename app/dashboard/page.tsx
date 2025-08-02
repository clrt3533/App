"use client"

import { useSession, signOut } from "next-auth/react"
import { useRouter } from "next/navigation"
import { useEffect } from "react"
import { Plus, Box, FileText, Users, LogOut, User } from 'lucide-react'
import Link from 'next/link'

export default function Dashboard() {
  const { data: session, status } = useSession()
  const router = useRouter()

  useEffect(() => {
    if (status === "unauthenticated") {
      router.push("/auth/signin")
    }
  }, [status, router])

  if (status === "loading") {
    return (
      <div className="min-h-screen bg-slate-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
          <p className="text-slate-600">Loading your dashboard...</p>
        </div>
      </div>
    )
  }

  if (!session) {
    return null
  }

  const handleSignOut = () => {
    signOut({ callbackUrl: "/" })
  }

  return (
    <div className="min-h-screen bg-slate-50">
      {/* Header */}
      <header className="bg-white border-b border-slate-200">
        <div className="px-6 py-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-4">
              <Link href="/" className="flex items-center space-x-2">
                <Box className="h-8 w-8 text-blue-600" />
                <span className="text-xl font-bold text-slate-900">PackagePro</span>
              </Link>
            </div>
            <div className="flex items-center space-x-4">
              <button className="inline-flex h-10 items-center justify-center rounded-md bg-blue-600 px-4 text-sm font-medium text-white shadow hover:bg-blue-700 transition-colors">
                <Plus className="h-4 w-4 mr-2" />
                New Project
              </button>
              <div className="flex items-center space-x-3">
                <div className="flex items-center space-x-2">
                  {session.user?.image ? (
                    <img
                      src={session.user.image}
                      alt={session.user.name || "User"}
                      className="h-8 w-8 rounded-full"
                    />
                  ) : (
                    <div className="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center">
                      <User className="h-4 w-4 text-slate-600" />
                    </div>
                  )}
                  <span className="text-sm font-medium text-slate-700">
                    {session.user?.name || session.user?.email}
                  </span>
                </div>
                <button
                  onClick={handleSignOut}
                  className="p-2 text-slate-400 hover:text-slate-600 transition-colors"
                  title="Sign out"
                >
                  <LogOut className="h-4 w-4" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>

      {/* Main Content */}
      <main className="px-6 py-8">
        <div className="max-w-7xl mx-auto">
          {/* Welcome Section */}
          <div className="mb-8">
            <h1 className="text-2xl font-bold text-slate-900 mb-2">
              Welcome back, {session.user?.name?.split(' ')[0] || 'Designer'}! 👋
            </h1>
            <p className="text-slate-600">
              Ready to create amazing packaging designs with AI?
            </p>
          </div>

          {/* Quick Stats */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div className="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
              <div className="flex items-center">
                <div className="flex-shrink-0">
                  <Box className="h-8 w-8 text-blue-600" />
                </div>
                <div className="ml-4">
                  <p className="text-sm font-medium text-slate-500">Projects</p>
                  <p className="text-2xl font-semibold text-slate-900">0</p>
                </div>
              </div>
            </div>
            <div className="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
              <div className="flex items-center">
                <div className="flex-shrink-0">
                  <FileText className="h-8 w-8 text-green-600" />
                </div>
                <div className="ml-4">
                  <p className="text-sm font-medium text-slate-500">Templates</p>
                  <p className="text-2xl font-semibold text-slate-900">200+</p>
                </div>
              </div>
            </div>
            <div className="bg-white rounded-lg border border-slate-200 p-6 shadow-sm">
              <div className="flex items-center">
                <div className="flex-shrink-0">
                  <Users className="h-8 w-8 text-purple-600" />
                </div>
                <div className="ml-4">
                  <p className="text-sm font-medium text-slate-500">Credits</p>
                  <p className="text-2xl font-semibold text-slate-900">10</p>
                </div>
              </div>
            </div>
          </div>

          {/* Getting Started */}
          <div className="bg-white rounded-lg border border-slate-200 p-8 shadow-sm">
            <h2 className="text-lg font-semibold text-slate-900 mb-4">
              Getting Started with PackagePro
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="flex items-start space-x-3">
                <div className="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <span className="text-sm font-semibold text-blue-600">1</span>
                </div>
                <div>
                  <h3 className="font-medium text-slate-900">Choose a Template</h3>
                  <p className="text-sm text-slate-600 mt-1">
                    Browse our library of 200+ professional packaging templates
                  </p>
                </div>
              </div>
              <div className="flex items-start space-x-3">
                <div className="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <span className="text-sm font-semibold text-blue-600">2</span>
                </div>
                <div>
                  <h3 className="font-medium text-slate-900">AI-Powered Design</h3>
                  <p className="text-sm text-slate-600 mt-1">
                    Let AI suggest perfect layouts, colors, and typography for your brand
                  </p>
                </div>
              </div>
              <div className="flex items-start space-x-3">
                <div className="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <span className="text-sm font-semibold text-blue-600">3</span>
                </div>
                <div>
                  <h3 className="font-medium text-slate-900">Preview in 3D</h3>
                  <p className="text-sm text-slate-600 mt-1">
                    See your design come to life with realistic 3D visualization
                  </p>
                </div>
              </div>
              <div className="flex items-start space-x-3">
                <div className="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <span className="text-sm font-semibold text-blue-600">4</span>
                </div>
                <div>
                  <h3 className="font-medium text-slate-900">Export & Share</h3>
                  <p className="text-sm text-slate-600 mt-1">
                    Download high-resolution files ready for production
                  </p>
                </div>
              </div>
            </div>
            <div className="mt-6 flex gap-4">
              <button className="inline-flex h-11 items-center justify-center rounded-md bg-blue-600 px-6 text-sm font-medium text-white shadow hover:bg-blue-700 transition-colors">
                <Plus className="h-4 w-4 mr-2" />
                Create Your First Project
              </button>
              <Link 
                href="/templates"
                className="inline-flex h-11 items-center justify-center rounded-md border border-slate-300 bg-white px-6 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition-colors"
              >
                Browse Templates
              </Link>
            </div>
          </div>
        </div>
      </main>
    </div>
  )
}