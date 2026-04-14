import re
import os
import struct
import sys

# Paths
POT_FILE = '../languages/auriane.pot'
PO_FILE = '../languages/pt_BR.po'
MO_FILE = '../languages/pt_BR.mo'

# Translation Dictionary (English -> Portuguese)
TRANSLATIONS = {
    # General
    "Home": "Início",
    "About Us": "Sobre Nós",
    "Contact": "Contato",
    "Search": "Pesquisar",
    "Menu": "Menu",
    "Close": "Fechar",
    "Submit": "Enviar",
    "Send": "Enviar",
    "Read More": "Leia Mais",
    "View More": "Ver Mais",
    "Show More": "Mostrar Mais",
    "Details": "Detalhes",
    "Description": "Descrição",
    "Date": "Data",
    "Author": "Autor",
    "Category": "Categoria",
    "Categories": "Categorias",
    "Tags": "Tags",
    "Comments": "Comentários",
    "Edit": "Editar",
    "Reply": "Responder",
    "Leave a Reply": "Deixe uma resposta",
    "Cancel Reply": "Cancelar resposta",
    "Search results for": "Resultados da pesquisa para",
    "Page not found": "Página não encontrada",
    "Error 404": "Erro 404",
    "Back to Home": "Voltar para o Início",
    "Previous": "Anterior",
    "Next": "Próximo",
    "Loading": "Carregando",
    "All Rights Reserved": "Todos os direitos reservados",
    "Copyright": "Direitos Autorais",
    
    # Shop / WooCommerce
    "Shop": "Loja",
    "Cart": "Carrinho",
    "Checkout": "Finalizar Compra",
    "My Account": "Minha Conta",
    "Product": "Produto",
    "Products": "Produtos",
    "Price": "Preço",
    "Quantity": "Quantidade",
    "Total": "Total",
    "Subtotal": "Subtotal",
    "Add to cart": "Adicionar ao carrinho",
    "Add to Cart": "Adicionar ao carrinho",
    "Buy Now": "Comprar Agora",
    "view cart": "ver carrinho",
    "View Cart": "Ver Carrinho",
    "Proceed to checkout": "Ir para o pagamento",
    "Place order": "Finalizar pedido",
    "Coupon": "Cupom",
    "Apply Coupon": "Aplicar Cupom",
    "Coupon code": "Código do cupom",
    "Update Cart": "Atualizar Carrinho",
    "Shipping": "Frete",
    "Free Shipping": "Frete Grátis",
    "Flat Rate": "Taxa Fixa",
    "Local Pickup": "Retirada no Local",
    "Billing details": "Detalhes de faturamento",
    "Shipping details": "Detalhes de entrega",
    "Order Notes": "Notas do pedido",
    "Payment Methods": "Métodos de Pagamento",
    "Cash on delivery": "Pagamento na entrega",
    "Direct bank transfer": "Transferência bancária direta",
    "Check payments": "Pagamentos por cheque",
    "PayPal": "PayPal",
    "Make your payment directly into our bank account.": "Faça seu pagamento diretamente em nossa conta bancária.",
    "Your order": "Seu pedido",
    "Clear cart": "Limpar carrinho",
    "Continue Shopping": "Continuar comprando",
    "Sale!": "Oferta!",
    "Out of stock": "Esgotado",
    "In stock": "Em estoque",
    "SKU": "SKU",
    "Related Products": "Produtos Relacionados",
    "You may also like": "Você também pode gostar",
    "Reviews": "Avaliações",
    "Review": "Avaliação",
    "There are no reviews yet.": "Ainda não há avaliações.",
    "Be the first to review": "Seja o primeiro a avaliar",
    "Your email address will not be published.": "Seu endereço de e-mail não será publicado.",
    "Required fields are marked": "Campos obrigatórios são marcados",
    "Your rating": "Sua classificação",
    "Your review": "Sua avaliação",
    "Name": "Nome",
    "Email": "E-mail",
    "Website": "Site",
    "Save my name, email, and website in this browser for the next time I comment.": "Salvar meu nome, e-mail e site neste navegador para a próxima vez que eu comentar.",
    
    # Account
    "Login": "Entrar",
    "Register": "Registrar",
    "Username": "Nome de usuário",
    "Password": "Senha",
    "Lost your password?": "Perdeu sua senha?",
    "Remember me": "Lembrar-me",
    "Log in": "Entrar",
    "Sign up": "Cadastrar",
    "Sign in": "Entrar",
    "Dashboard": "Painel",
    "Orders": "Pedidos",
    "Downloads": "Downloads",
    "Addresses": "Endereços",
    "Account details": "Detalhes da conta",
    "Logout": "Sair",
    "Hello": "Olá",
    "From your account dashboard you can view your": "No painel da sua conta você pode ver seus",
    "recent orders": "pedidos recentes",
    "manage your shipping and billing addresses": "gerenciar seus endereços de entrega e cobrança",
    "and": "e",
    "edit your password and account details": "editar sua senha e detalhes da conta",
    
    # Theme Specific / Common Variations
    "Search for:": "Pesquisar por:",
    "Nothing Found": "Nada Encontrado",
    "It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.": "Parece que não conseguimos encontrar o que você procura. Talvez pesquisar ajude.",
    "Sorry, but nothing matched your search terms. Please try again with some different keywords.": "Desculpe, mas nada correspondeu aos seus termos de pesquisa. Tente novamente com palavras-chave diferentes.",
    "Category Archives: %s": "Arquivos da Categoria: %s",
    "Tag Archives: %s": "Arquivos da Tag: %s",
    "Author Archives: %s": "Arquivos do Autor: %s",
    "Weekly Archives: %s": "Arquivos Semanais: %s",
    "Monthly Archives: %s": "Arquivos Mensais: %s",
    "Yearly Archives: %s": "Arquivos Anuais: %s",
    "Archives": "Arquivos",
    "Meta": "Meta",
    "Site Admin": "Admin do Site",
    "Log out": "Sair",
    "Entries feed": "Feed de entradas",
    "Comments feed": "Feed de comentários",
    "WordPress.org": "WordPress.org",
    "Select options": "Selecione as opções",
    "Select": "Selecionar",
    "Filter": "Filtrar",
    "Reset": "Redefinir",
    "Show": "Mostrar",
    "Sort by": "Ordenar por",
    "Default sorting": "Ordenação padrão",
    "Sort by popularity": "Ordenar por popularidade",
    "Sort by average rating": "Ordenar por avaliação média",
    "Sort by latest": "Ordenar por mais recentes",
    "Sort by price: low to high": "Ordenar por preço: menor para maior",
    "Sort by price: high to low": "Ordenar por preço: maior para menor",
    "Wishlist": "Lista de Desejos",
    "Compare": "Comparar",
    "Share": "Compartilhar",
    "Quick View": "Visualização Rápida",
    "Discount": "Desconto",
    "New": "Novo",
    "Featured": "Destaque",
    "Hot": "Quente",
    
    # Auriane specific (From File Overview)
    "All Items": "Todos os Itens",
    "Active": "Ativo",
    "Add New": "Adicionar Novo",
    "Add New Item": "Adicionar Novo Item",
    "Address Information": "Informações de Endereço",
    "Address Title": "Título do Endereço",
    "Addresses List": "Lista de Endereços",
    "Advanced": "Avançado",
    "After": "Depois",
    "Align": "Alinhar",
    "Alignment": "Alinhamento",
    "All": "Todos",
    "All categories": "Todas as categorias",
    "All posts": "Todos os posts",
    "All Projects": "Todos os Projetos",
    "Add to wishlist": "Adicionar à lista de desejos",
    "Add to compare": "Adicionar para comparar",
    " (in cart)": " (no carrinho)",
    " free shipping": " frete grátis",
    " items": " itens",
    " like": " curtir",
    " likes": " curtidas",
    " Only": " Apenas",
    " Sold": " Vendido",
    " State*": " Estado*",
    " to reach": " para alcançar",
    "%1$s": "%1$s",
    "%s": "%s",
    "%s comment": "%s comentário",
    "%s comments": "%s comentários",
    "(estimated for %s)": "(estimado para %s)",
    "/month ": "/mês ",
    "404 Page": "Página 404",
    "Add Image": "Adicionar Imagem",
    "Add Your Heading Text Here": "Adicione seu texto de título aqui",
    "Additional information": "Informação Adicional",
    
    # Formats needing careful handling
    # We will try to match exact strings first
}

# Simple regex to approximate pluralization choice
def select_plural(msgid, msgid_plural, n):
    # Portuguese matches English plural logic (n != 1)
    if n == 1:
        return msgid
    return msgid_plural

def parse_po_entry(entry_lines):
    entry = {}
    msgid = ""
    msgid_plural = ""
    msgstr = ""
    ctx = None
    
    # Simple parsing logic
    current_field = None
    
    for line in entry_lines:
        line = line.strip()
        if not line: continue
        
        # Check for comments
        if line.startswith("#"):
            if "comments" not in entry: entry["comments"] = []
            entry["comments"].append(line)
            continue
            
        # Match fields
        # Note: minimal parser, assumes valid POT structure
        if line.startswith('msgctxt'):
            current_field = 'msgctxt'
            ctx = line.split(' ', 1)[1].strip('"')
            entry['msgctxt'] = ctx
        elif line.startswith('msgid_plural'):
            current_field = 'msgid_plural'
            msgid_plural = line.split(' ', 1)[1].strip('"')
            entry['msgid_plural'] = msgid_plural
        elif line.startswith('msgid'):
            current_field = 'msgid'
            msgid = line.split(' ', 1)[1].strip('"')
            entry['msgid'] = msgid
        elif line.startswith('msgstr'):
            current_field = 'msgstr'
            # Check for array index [0]
            parts = line.split(' ', 1)
            if len(parts) > 1:
                val = parts[1].strip('"')
            else:
                val = ""
            entry['msgstr'] = val
        elif line.startswith('"'):
            # Continuation
            if current_field == 'msgid':
                entry['msgid'] += line.strip('"')
            elif current_field == 'msgid_plural':
                entry['msgid_plural'] += line.strip('"')
            elif current_field == 'msgctxt':
                entry['msgctxt'] += line.strip('"')
    
    return entry

def generate_mo_file(po_content, mo_filepath):
    # This is a simplified MO generator
    # Structure:
    # Magic number
    # File format revision
    # Number of strings
    # Offset of table with original strings
    # Offset of table with translation strings
    # Size of hashing table
    # Offset of hashing table
    
    messages = [] # list of (msgid, msgstr) tuples
    
    # Parse the PO content we just generated
    # This is a bit redundant but safe. 
    # Actually, we can just use the data we have in memory if we structure it right.
    pass

def main():
    print(f"Reading {POT_FILE}...")
    try:
        with open(POT_FILE, 'r', encoding='utf-8') as f:
            lines = f.readlines()
    except FileNotFoundError:
        print(f"Error: {POT_FILE} not found.")
        return

    output_lines = []
    
    # Header
    output_lines.append('msgid ""\n')
    output_lines.append('msgstr ""\n')
    output_lines.append('"Project-Id-Version: Auriane\\n"\n')
    output_lines.append('"MIME-Version: 1.0\\n"\n')
    output_lines.append('"Content-Type: text/plain; charset=UTF-8\\n"\n')
    output_lines.append('"Content-Transfer-Encoding: 8bit\\n"\n')
    output_lines.append('"Language: pt_BR\\n"\n')
    output_lines.append('"Plural-Forms: nplurals=2; plural=(n > 1);\\n"\n')
    output_lines.append('\n')
    
    current_entry = []
    translated_count = 0
    total_count = 0
    
    messages_for_mo = {} # msgid -> msgstr
    
    for line in lines:
        line = line.strip()
        
        if line.startswith('msgid ""') and len(current_entry) == 0:
             # Skip header in loop, handled manually
             continue
             
        if line.strip() == "" or line.startswith("#"):
            # comments or empty lines
            if line.startswith("#"):
                current_entry.append(line)
            else:
                if current_entry:
                    process_entry(current_entry, output_lines, messages_for_mo)
                    current_entry = []
                    total_count += 1
                output_lines.append(line)
        else:
            current_entry.append(line)
            
    # Process last entry
    if current_entry:
        process_entry(current_entry, output_lines, messages_for_mo)
        total_count += 1

    print(f"Translated approximately {len(messages_for_mo)} strings.")
    
    print(f"Writing {PO_FILE}...")
    with open(PO_FILE, 'w', encoding='utf-8') as f:
        f.writelines(output_lines)

    # MO generation
    print(f"Writing {MO_FILE}...")
    write_mo_file(MO_FILE, messages_for_mo)
    
def process_entry(entry_lines, output_lines, messages_for_mo):
    # Extract msgid
    msgid = ""
    msgid_plural = ""
    is_plural = False
    context = ""
    
    # Very basic parser for the current block
    # Concatenate multiline strings
    
    temp_id = ""
    mode = None
    
    for line in entry_lines:
        line = line.strip()
        if line.startswith('msgctxt'):
            mode = 'ctxt'
            context = line.split(' ', 1)[1].strip('"')
        elif line.startswith('msgid_plural'):
            mode = 'plural'
            is_plural = True
            msgid_plural = line.split(' ', 1)[1].strip('"')
        elif line.startswith('msgid'):
            mode = 'id'
            msgid = line.split(' ', 1)[1].strip('"')
        elif line.startswith('"') and mode:
            val = line.strip('"')
            if mode == 'id': msgid += val
            elif mode == 'plural': msgid_plural += val
            elif mode == 'ctxt': context += val
            
    # Lookup
    translation = ""
    translation_plural = ""
    
    # Try exact match
    if msgid in TRANSLATIONS:
        translation = TRANSLATIONS[msgid]
    elif msgid.lower() in [k.lower() for k in TRANSLATIONS.keys()]:
        # Case insensitive fallback
        for k, v in TRANSLATIONS.items():
            if k.lower() == msgid.lower():
                translation = v
                break
    
    # Heuristics for common patterns
    if not translation:
        if msgid.endswith(" Settings"):
            base = msgid.replace(" Settings", "")
            if base in TRANSLATIONS:
                translation = "Configurações de " + TRANSLATIONS[base]
        
    # Write back to output
    # We reconstruct the entry with the new msgstr
    
    # Copy comments
    for line in entry_lines:
        if line.startswith("#"):
            output_lines.append(line)
            
    if context:
        output_lines.append(f'msgctxt "{context}"\n')
        
    output_lines.append(f'msgid "{msgid}"\n')
    
    if is_plural:
        output_lines.append(f'msgid_plural "{msgid_plural}"\n')
        # Plural logic
        t1 = translation if translation else msgid
        t2 = translation_plural if translation_plural else (msgid_plural if msgid_plural else msgid + "s")
        
        # If we have a translation key that might be relevant for plural?
        # For now, if we mapped the singular, we might just assume plural is singular + s if not in dict
        if translation and not translation_plural:
            # simple pluralize rule for PT
            if translation.endswith('m'):
                t2 = translation[:-1] + 'ns'
            elif translation.endswith('r') or translation.endswith('z') or translation.endswith('s'):
                t2 = translation + 'es'
            else:
                t2 = translation + 's'
                
        output_lines.append(f'msgstr[0] "{t1}"\n')
        output_lines.append(f'msgstr[1] "{t2}"\n')
        
        # MO storage: key is msgid (with context if exists? complex topic. keeping simple)
        # MO usually separates context with EOT (0x04)
        full_key = (context + '\x04' + msgid) if context else msgid
        # Plurals in MO are stored as single string separated by NUL
        full_val = t1 + '\x00' + t2
        messages_for_mo[full_key] = full_val
        
    else:
        output_lines.append(f'msgstr "{translation}"\n')
        full_key = (context + '\x04' + msgid) if context else msgid
        messages_for_mo[full_key] = translation

    output_lines.append('\n')

def write_mo_file(filename, messages):
    # messages is dict: msgid -> msgstr
    # Keys should be sorted
    sorted_keys = sorted(messages.keys())
    
    count = len(sorted_keys)
    
    # Offsets
    # Header: 7 integers (28 bytes)
    keystart = 28
    valstart = keystart + (8 * count) # 8 bytes per key entry (length, offset)
    # values start after key entries and val entries
    # actually typical structure:
    # Header
    # O (Original) Table: 8 bytes * count
    # T (Translation) Table: 8 bytes * count
    # Data area (keys and values mixed or sequential)
    
    # We will put keys then values
    key_table_offset = 28
    val_table_offset = key_table_offset + (8 * count)
    data_start_offset = val_table_offset + (8 * count)
    
    curr_offset = data_start_offset
    
    key_descriptors = []
    val_descriptors = []
    data_buffer = bytearray()
    
    for k in sorted_keys:
        v = messages[k]
        
        # Key
        k_bytes = k.encode('utf-8')
        k_len = len(k_bytes)
        key_descriptors.append((k_len, curr_offset))
        data_buffer.extend(k_bytes)
        data_buffer.append(0) # NUL
        curr_offset += k_len + 1
    
    # Values need to be processed after keys to determine their offsets? 
    # No, we can append to buffer sequentially, but we need to track offsets for the tables.
    # Actually standard MO usually groups all keys then all values, or interleaves.
    # We'll just continue appending to data_buffer.
    
    for k in sorted_keys:
        v = messages[k]
        v_bytes = v.encode('utf-8')
        v_len = len(v_bytes)
        val_descriptors.append((v_len, curr_offset))
        data_buffer.extend(v_bytes)
        data_buffer.append(0)
        curr_offset += v_len + 1
        
    # Write file
    with open(filename, 'wb') as f:
        # Magic
        f.write(struct.pack('I', 0x950412de))
        # Revision
        f.write(struct.pack('I', 0))
        # Count
        f.write(struct.pack('I', count))
        # Offset of original substrings
        f.write(struct.pack('I', key_table_offset))
        # Offset of translation substrings
        f.write(struct.pack('I', val_table_offset))
        # Size of hash table (0)
        f.write(struct.pack('I', 0))
        # Offset of hash table (0)
        f.write(struct.pack('I', 0))
        
        # Key Table
        for length, offset in key_descriptors:
            f.write(struct.pack('II', length, offset))
            
        # Value Table
        for length, offset in val_descriptors:
            f.write(struct.pack('II', length, offset))
            
        # Data
        f.write(data_buffer)

if __name__ == "__main__":
    main()
