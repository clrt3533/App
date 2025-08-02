# 🚀 PackagePro Deployment Guide

This guide covers different deployment strategies for PackagePro, from local development to production environments.

## 🏗️ **Deployment Options Overview**

| Platform | Difficulty | Cost | Best For |
|----------|------------|------|----------|
| **Vercel** | ⭐ Easy | Free → $20/mo | Quick MVP launch |
| **Railway** | ⭐⭐ Easy | $5 → $20/mo | Full-stack with DB |
| **DigitalOcean** | ⭐⭐⭐ Medium | $12 → $50/mo | Scalable production |
| **AWS/GCP/Azure** | ⭐⭐⭐⭐ Hard | $20+ | Enterprise scale |

---

## 🎯 **Quick Deploy (5 minutes)**

### **Vercel (Recommended for MVP)**

1. **Connect Repository**
   ```bash
   # Push your code to GitHub first
   git add .
   git commit -m "Initial commit"
   git push origin main
   ```

2. **Deploy to Vercel**
   - Visit [vercel.com](https://vercel.com)
   - Click "New Project"
   - Import from GitHub
   - Select your repository
   - Deploy automatically!

3. **Environment Variables**
   ```env
   DATABASE_URL=postgresql://username:password@host:port/database
   NEXTAUTH_SECRET=your-secret-key
   NEXTAUTH_URL=https://your-app.vercel.app
   ```

4. **Database Setup**
   - Use [Neon](https://neon.tech) or [Supabase](https://supabase.com) for PostgreSQL
   - Copy connection string to `DATABASE_URL`
   - Run migrations: `npx prisma migrate deploy`

**✅ Your app is live in 5 minutes!**

---

## 🚂 **Railway Deployment**

### **Step 1: Setup Railway**
```bash
# Install Railway CLI
npm install -g @railway/cli

# Login
railway login

# Initialize project
railway init
```

### **Step 2: Configure Services**
```bash
# Add PostgreSQL database
railway add postgresql

# Deploy application
railway up
```

### **Step 3: Environment Variables**
```bash
# Set environment variables
railway variables set DATABASE_URL=${{ POSTGRES.DATABASE_URL }}
railway variables set NEXTAUTH_SECRET=your-secret-key
railway variables set NEXTAUTH_URL=https://your-app.railway.app
```

### **Step 4: Custom Domain (Optional)**
```bash
# Add custom domain
railway domain add yourdomain.com
```

---

## 🐳 **Docker Production Deployment**

### **DigitalOcean App Platform**

1. **Create App**
   ```yaml
   # .do/app.yaml
   name: packagepro
   services:
   - name: web
     source_dir: /
     github:
       repo: your-username/packagepro
       branch: main
     dockerfile_path: Dockerfile
     build_command: |
       npm run build
     run_command: |
       npm start
     environment_slug: node-js
     instance_count: 1
     instance_size_slug: basic-xxs
     routes:
     - path: /
     envs:
     - key: DATABASE_URL
       value: ${postgresql.DATABASE_URL}
     - key: NEXTAUTH_SECRET
       value: your-secret-key
   
   databases:
   - name: postgresql
     engine: PG
     version: "14"
     size: basic-xs
   ```

2. **Deploy**
   ```bash
   # Install doctl CLI
   # Create app from spec
   doctl apps create --spec .do/app.yaml
   ```

### **AWS ECS/Fargate**

1. **Build and Push Image**
   ```bash
   # Build image
   docker build -t packagepro:latest .
   
   # Tag for ECR
   docker tag packagepro:latest your-account.dkr.ecr.region.amazonaws.com/packagepro:latest
   
   # Push to ECR
   docker push your-account.dkr.ecr.region.amazonaws.com/packagepro:latest
   ```

2. **ECS Task Definition**
   ```json
   {
     "family": "packagepro",
     "networkMode": "awsvpc",
     "requiresCompatibilities": ["FARGATE"],
     "cpu": "256",
     "memory": "512",
     "containerDefinitions": [
       {
         "name": "packagepro",
         "image": "your-account.dkr.ecr.region.amazonaws.com/packagepro:latest",
         "portMappings": [
           {
             "containerPort": 3000,
             "protocol": "tcp"
           }
         ],
         "environment": [
           {
             "name": "DATABASE_URL",
             "value": "your-postgres-url"
           }
         ]
       }
     ]
   }
   ```

---

## 🔄 **CI/CD Setup**

### **GitHub Actions (Included)**

Our CI/CD pipeline automatically:
- ✅ **Tests** on every PR
- ✅ **Builds** Docker images
- ✅ **Deploys** to staging/production
- ✅ **Security scans** with Trivy
- ✅ **Performance audits** with Lighthouse

### **Required Secrets**

Set these in GitHub Settings > Secrets:

```bash
# Vercel Deployment
VERCEL_TOKEN=your-vercel-token
VERCEL_ORG_ID=your-org-id
VERCEL_PROJECT_ID=your-project-id

# Database
DATABASE_URL=postgresql://...

# Authentication
NEXTAUTH_SECRET=your-secret-key
```

### **Branch Strategy**
- `main` → Production deployment
- `develop` → Staging deployment
- `feature/*` → Preview deployments

---

## 📊 **Environment Configuration**

### **Development (.env.local)**
```env
DATABASE_URL="file:./dev.db"
NEXTAUTH_URL="http://localhost:3000"
NEXTAUTH_SECRET="dev-secret"
```

### **Staging (.env.staging)**
```env
DATABASE_URL="postgresql://staging-db-url"
NEXTAUTH_URL="https://staging.packagepro.com"
NEXTAUTH_SECRET="staging-secret"
```

### **Production (.env.production)**
```env
DATABASE_URL="postgresql://production-db-url"
NEXTAUTH_URL="https://packagepro.com"
NEXTAUTH_SECRET="production-secret"
AWS_ACCESS_KEY_ID="your-key"
AWS_SECRET_ACCESS_KEY="your-secret"
AWS_BUCKET_NAME="packagepro-assets"
```

---

## 🔒 **Security Checklist**

### **Before Production**
- [ ] Update `NEXTAUTH_SECRET` to secure random string
- [ ] Set up HTTPS with SSL certificate
- [ ] Configure CORS policies
- [ ] Enable rate limiting
- [ ] Set up monitoring (Sentry, LogRocket)
- [ ] Configure backup strategy
- [ ] Set up uptime monitoring

### **Database Security**
- [ ] Use connection pooling
- [ ] Enable SSL for database connections
- [ ] Regular automated backups
- [ ] Monitor query performance
- [ ] Set up read replicas for scaling

---

## 📈 **Scaling Strategy**

### **Phase 1: MVP (0-1K users)**
- **Platform**: Vercel + Neon PostgreSQL
- **Cost**: ~$0-20/month
- **Setup Time**: 1 hour

### **Phase 2: Growth (1K-10K users)**
- **Platform**: Railway or DigitalOcean
- **Database**: Dedicated PostgreSQL
- **CDN**: Cloudflare
- **Cost**: ~$50-200/month
- **Setup Time**: 1 day

### **Phase 3: Scale (10K+ users)**
- **Platform**: AWS/GCP with Kubernetes
- **Database**: Multi-region PostgreSQL
- **CDN**: Global CDN with edge caching
- **Monitoring**: Full observability stack
- **Cost**: $500+/month
- **Setup Time**: 1 week

---

## 🚨 **Monitoring & Alerts**

### **Essential Monitoring**
```bash
# Uptime monitoring
curl -f https://packagepro.com/health || exit 1

# Performance monitoring
lighthouse --only-categories=performance https://packagepro.com

# Error tracking
# Integrate Sentry for error monitoring
```

### **Key Metrics**
- **Response Time**: < 500ms (95th percentile)
- **Uptime**: > 99.9%
- **Error Rate**: < 0.1%
- **Core Web Vitals**: Green scores

---

## 🆘 **Troubleshooting**

### **Common Issues**

1. **Build Failures**
   ```bash
   # Clear cache and rebuild
   rm -rf .next node_modules
   npm install
   npm run build
   ```

2. **Database Connection Issues**
   ```bash
   # Test database connection
   npx prisma db pull
   ```

3. **Environment Variables**
   ```bash
   # Verify environment variables
   printenv | grep DATABASE_URL
   ```

### **Health Check Endpoint**
```typescript
// app/api/health/route.ts
export async function GET() {
  return Response.json({ 
    status: 'healthy', 
    timestamp: new Date().toISOString() 
  })
}
```

---

## 📞 **Support**

- **Documentation**: [docs.packagepro.com](https://docs.packagepro.com)
- **Issues**: [GitHub Issues](../../issues)
- **Discord**: [PackagePro Community](https://discord.gg/packagepro)
- **Email**: devops@packagepro.com

---

**🎉 Happy Deploying!**