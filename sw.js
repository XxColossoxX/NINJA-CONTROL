// Service Worker para Ninja Control PWA
const CACHE_NAME = 'ninja-control-v1.0.0';
const STATIC_CACHE_NAME = 'ninja-control-static-v1.0.0';
const DYNAMIC_CACHE_NAME = 'ninja-control-dynamic-v1.0.0';

// Arquivos estáticos para cache
const STATIC_FILES = [
  '/',
  '/index.php',
  '/indexLogin.php',
  '/offline.html',
  '/assets/css/tailwind.min.css',
  '/assets/css/fontawesome.min.css',
  '/assets/js/jquery.min.js',
  '/assets/img/ninjaLogo.png',
  '/assets/img/favicon/favicon-96x96.png',
  '/assets/img/favicon/icon192.png',
  '/assets/img/favicon/icon512.png',
  '/assets/img/favicon/apple-touch-icon.png',
  '/assets/img/favicon/favicon.ico',
  '/assets/img/favicon/favicon.svg',
  '/assets/img/saibaFoto.png',
  '/assets/img/saibaFotoMobi.png',
  '/assets/img/saibaFotoDesk.png',
  '/assets/img/painelPrint.png',
  '/assets/img/pontoPrint.png',
  '/assets/img/ninjaEmoji.png',
  '/assets/img/wallpaper.jpg',
  '/assets/manifest.json',
  // Páginas principais
  '/views/loginFuncionario/loginFuncionario.php',
  '/views/loginEmpresa/loginEmpresa.php',
  '/views/pontoFuncionario/pontoFuncionario.php',
  '/views/pontoEmpresa/pontoEmpresa.php',
  '/views/historicoPonto/historicoPonto.php',
  '/views/painelPrincipal/painelPrincipal.php',
  '/views/dadosEmpresa/dadosEmpresa.php',
  '/views/dadosEmpresaAdmin/dadosEmpresaAdmin.php',
  '/views/registroEmpresa/registroEmpresa.php',
  // Componentes
  '/assets/components/background.php',
  '/assets/components/headerFuncionario.php',
  '/assets/components/headerEmpresa.php'
];

// Instalação do Service Worker
self.addEventListener('install', (event) => {
  console.log('[SW] Instalando Service Worker...');
  
  event.waitUntil(
    caches.open(STATIC_CACHE_NAME)
      .then((cache) => {
        console.log('[SW] Cacheando arquivos estáticos...');
        return cache.addAll(STATIC_FILES);
      })
      .then(() => {
        console.log('[SW] Instalação concluída!');
        return self.skipWaiting();
      })
      .catch((error) => {
        console.error('[SW] Erro na instalação:', error);
      })
  );
});

// Ativação do Service Worker
self.addEventListener('activate', (event) => {
  console.log('[SW] Ativando Service Worker...');
  
  event.waitUntil(
    caches.keys()
      .then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cacheName) => {
            if (cacheName !== STATIC_CACHE_NAME && cacheName !== DYNAMIC_CACHE_NAME) {
              console.log('[SW] Removendo cache antigo:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => {
        console.log('[SW] Ativação concluída!');
        return self.clients.claim();
      })
  );
});

// Interceptação de requisições
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Estratégia para diferentes tipos de requisições
  if (request.method === 'GET') {
    // Para arquivos estáticos (CSS, JS, imagens)
    if (url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf)$/)) {
      event.respondWith(
        caches.match(request)
          .then((response) => {
            if (response) {
              return response;
            }
            
            return fetch(request)
              .then((response) => {
                if (response.status === 200) {
                  const responseClone = response.clone();
                  caches.open(STATIC_CACHE_NAME)
                    .then((cache) => {
                      cache.put(request, responseClone);
                    });
                }
                return response;
              })
              .catch(() => {
                // Fallback para imagens
                if (url.pathname.match(/\.(png|jpg|jpeg|gif|svg|ico)$/)) {
                  return caches.match('/assets/img/ninjaLogo.png');
                }
              });
          })
      );
    }
    // Para páginas HTML/PHP
    else if (url.pathname.match(/\.(php|html)$/) || url.pathname === '/') {
      event.respondWith(
        caches.match(request)
          .then((response) => {
            if (response) {
              return response;
            }
            
            return fetch(request)
              .then((response) => {
                if (response.status === 200) {
                  const responseClone = response.clone();
                  caches.open(DYNAMIC_CACHE_NAME)
                    .then((cache) => {
                      cache.put(request, responseClone);
                    });
                }
                return response;
              })
              .catch(() => {
                // Página offline para navegação
                if (request.mode === 'navigate') {
                  return caches.match('/offline.html');
                }
              });
          })
      );
    }
    // Para API/Backend
    else if (url.pathname.includes('/backend/')) {
      event.respondWith(
        fetch(request)
          .then((response) => {
            // Cache apenas respostas bem-sucedidas da API
            if (response.status === 200) {
              const responseClone = response.clone();
              caches.open(DYNAMIC_CACHE_NAME)
                .then((cache) => {
                  cache.put(request, responseClone);
                });
            }
            return response;
          })
          .catch(() => {
            // Tentar buscar do cache se offline
            return caches.match(request)
              .then((response) => {
                if (response) {
                  return response;
                }
                return new Response(
                  JSON.stringify({ 
                    success: false, 
                    error: 'Sem conexão com a internet' 
                  }),
                  { 
                    status: 503,
                    headers: { 'Content-Type': 'application/json' }
                  }
                );
              });
          })
      );
    }
  }
});

// Notificações push (para futuras implementações)
self.addEventListener('push', (event) => {
  console.log('[SW] Push recebido:', event);
  
  const options = {
    body: event.data ? event.data.text() : 'Nova notificação do Ninja Control',
    icon: '/assets/img/favicon/icon192.png',
    badge: '/assets/img/favicon/favicon-96x96.png',
    vibrate: [200, 100, 200],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'Abrir App',
        icon: '/assets/img/favicon/icon192.png'
      },
      {
        action: 'close',
        title: 'Fechar',
        icon: '/assets/img/favicon/icon192.png'
      }
    ]
  };
  
  event.waitUntil(
    self.registration.showNotification('Ninja Control', options)
  );
});

// Clique em notificação
self.addEventListener('notificationclick', (event) => {
  console.log('[SW] Clique na notificação:', event);
  
  event.notification.close();
  
  if (event.action === 'explore') {
    event.waitUntil(
      clients.openWindow('/')
    );
  }
});

// Sincronização em background (para futuras implementações)
self.addEventListener('sync', (event) => {
  console.log('[SW] Sync event:', event.tag);
  
  if (event.tag === 'background-sync') {
    event.waitUntil(
      // Implementar sincronização de dados offline
      console.log('[SW] Sincronizando dados em background...')
    );
  }
});

// Mensagens do cliente
self.addEventListener('message', (event) => {
  console.log('[SW] Mensagem recebida:', event.data);
  
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'GET_VERSION') {
    event.ports[0].postMessage({ version: CACHE_NAME });
  }
});

console.log('[SW] Service Worker carregado!');
