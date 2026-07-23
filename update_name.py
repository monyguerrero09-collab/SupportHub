import re

file_path = r'c:\Users\monyg\OneDrive\Documentos\ticket_plataforma\resources\views\livewire\support-hub.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update aside classes to w-[400px] and fixed right-0
aside_regex = re.compile(
    r'<aside x-show="chatListSidebarOpen[^"]*" x-cloak style="display: none;"\s+class="[^"]*"',
    re.MULTILINE
)
new_aside = '''<aside x-show="chatListSidebarOpen || ($wire.chatWidgetTicketId && !$wire.isChatWidgetMinimized)" x-cloak style="display: none;"
       class="fixed right-0 top-0 bottom-0 h-screen w-[320px] sm:w-[360px] md:w-[400px] bg-[#0b0c16] flex flex-col border-l border-white/10 shadow-[-10px_0_30px_rgba(0,0,0,0.8)] z-[100] shrink-0 transition-all duration-300 origin-right"'''
content = aside_regex.sub(new_aside, content)

# 2. Update Alex R. to Bryan C.
content = content.replace('Alex R. (Soporte TI)', 'Bryan C. (Soporte TI)')
content = content.replace('name=A+R', 'name=B+C')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print("Updated support-hub.blade.php")
