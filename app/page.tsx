import Link from 'next/link'
import { ArrowRight, Box, Sparkles, Zap, Shield } from 'lucide-react'

export default function HomePage() {
  return (
    <div className="min-h-screen" style={{ background: 'linear-gradient(135deg, #eff6ff 0%, #ffffff 50%, #faf5ff 100%)' }}>
      {/* Navigation */}
      <nav className="container mx-auto px-6 py-4">
        <div className="flex items-center justify-between">
          <div className="flex items-center space-x-2">
            <div className="w-8 h-8 gradient-blue-purple rounded-lg flex items-center justify-center">
              <Box className="h-5 w-5 text-white" />
            </div>
            <span className="text-2xl font-bold gradient-text">
              PackagePro
            </span>
          </div>
          <div className="flex items-center space-x-4">
            <Link
              href="/auth/signin"
              className="text-gray-600 hover:text-gray-900 transition-colors"
            >
              Sign In
            </Link>
            <Link
              href="/auth/signup"
              className="btn btn-primary"
            >
              Get Started
            </Link>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <div className="container mx-auto px-6 py-20">
        <div className="text-center max-w-4xl mx-auto">
          <h1 className="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
            Design Professional 
            <span className="gradient-text">
              {" "}Packaging
            </span>
            <br />
            in Minutes with AI
          </h1>
          <p className="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Create stunning 3D packaging designs with our AI-powered platform. 
            From concept to creation, bring your brand to life with professional packaging.
          </p>
          <div className="flex flex-col md:flex-row gap-4 justify-center">
            <Link
              href="/auth/signup"
              className="btn btn-primary group"
            >
              Start Creating Free
              <ArrowRight className="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform" />
            </Link>
            <Link
              href="/templates"
              className="btn btn-secondary"
            >
              Browse Templates
            </Link>
          </div>
        </div>
      </div>

      {/* Features Section */}
      <div className="container mx-auto px-6 py-16">
        <div className="text-center mb-16">
          <h2 className="text-3xl font-bold text-gray-900 mb-4">
            Everything You Need to Create Amazing Packaging
          </h2>
          <p className="text-gray-600 max-w-2xl mx-auto">
            Powerful tools and AI assistance to help you design packaging that stands out on the shelf.
          </p>
        </div>

        <div className="grid md:grid-cols-3 gap-8">
          <div className="text-center p-6">
            <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <Box className="h-8 w-8 text-blue-600" />
            </div>
            <h3 className="text-xl font-semibold text-gray-900 mb-2">3D Design Editor</h3>
            <p className="text-gray-600">
              Real-time 3D packaging design with intuitive controls and professional templates.
            </p>
          </div>

          <div className="text-center p-6">
            <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <Sparkles className="h-8 w-8 text-purple-600" />
            </div>
            <h3 className="text-xl font-semibold text-gray-900 mb-2">AI-Powered Suggestions</h3>
            <p className="text-gray-600">
              Get intelligent design recommendations based on your brand and industry.
            </p>
          </div>

          <div className="text-center p-6">
            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <Zap className="h-8 w-8 text-green-600" />
            </div>
            <h3 className="text-xl font-semibold text-gray-900 mb-2">Export Ready Files</h3>
            <p className="text-gray-600">
              Export production-ready files in multiple formats for printing and prototyping.
            </p>
          </div>
        </div>
      </div>

      {/* CTA Section */}
      <div className="bg-gray-900 text-white py-16">
        <div className="container mx-auto px-6 text-center">
          <h2 className="text-3xl font-bold mb-4">Ready to Transform Your Packaging?</h2>
          <p className="text-gray-300 mb-8 max-w-2xl mx-auto">
            Join thousands of brands creating professional packaging with PackagePro's AI-powered design platform.
          </p>
          <Link
            href="/auth/signup"
            className="btn btn-primary inline-flex items-center"
          >
            Get Started for Free
            <ArrowRight className="ml-2 h-5 w-5" />
          </Link>
        </div>
      </div>

      {/* Footer */}
      <footer className="bg-white border-t border-gray-200 py-8">
        <div className="container mx-auto px-6">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-2">
              <div className="w-6 h-6 gradient-blue-purple rounded flex items-center justify-center">
                <Box className="h-4 w-4 text-white" />
              </div>
              <span className="font-semibold text-gray-900">PackagePro</span>
            </div>
            <div className="text-sm text-gray-500">
              © 2024 PackagePro. All rights reserved.
            </div>
          </div>
        </div>
      </footer>
    </div>
  )
}