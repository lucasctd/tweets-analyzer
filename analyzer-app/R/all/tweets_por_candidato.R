library(tm)
library(magrittr)
library(plotly)

source("shared/database.r")

res <- dbSendQuery(con, "select * from V_QUANTIDADE_TWEETS_CANDIDATO")
result <- fetch(res, n = -1)

cores <- c("red", "orange", "blue", "yellow", "green", "gray")

pre_candidatos <- c("Geraldo Alckmin", "Jair Bolsonaro", "Manuela D'\u00c1vila", "Marina Silva", "Ciro Gomes", "Jo\u00e3o Amo\u00eado")
data <- c(result$GERALDO_ALCKMIN, result$JAIR_BOLSONARO, result$MANUELA_DAVILA, result$MARINA_SILVA, result$CIRO_GOMES, result$JOAO_AMOEDO)
labels_pie <- paste(pre_candidatos, paste('(', data, ')', sep = ''), sep = ' ')

dataFrame <- data.frame(labels_pie, data)

#pie
p <- plot_ly(dataFrame, labels = ~labels_pie, values = ~data, type = 'pie') %>% 
         layout(title = paste('Tweets por pr\u00e9-candidato ', '(', sum(data), ')', sep = ''),
         showlegend = T,
         xaxis = list(showgrid = FALSE, zeroline = FALSE, showticklabels = FALSE),
         yaxis = list(showgrid = FALSE, zeroline = FALSE, showticklabels = FALSE))

#bar

p <- plot_ly(
  x = pre_candidatos,
  y = data,
  name = "Tweets por pr\u00e9-candidato",
  type = "bar",
  text = paste(round(data/sum(data)*100, 4), '%', sep = ''),
  textposition = 'auto',
  marker = list(color = c('#87CEFA', '#00FF7F', '#BDB76B', 'orange', '#CDB79E', '#00FFFF'))
)%>% layout(title = 'Tweets por pr\u00e9-candidato',
            xaxis = list(title = paste("Total:", sum(data), "tweets")),
            yaxis = list(title = "N\u00famero de Tweets"))
