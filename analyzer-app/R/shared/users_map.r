#map bolsonaro's followers
library(plotly)
library(magrittr)

source("shared/database.r")

renderUsersMap <- function(hashtags, breaks, breaks_labels, nome_candidato) {
  
  query <- "SELECT latitude, longitude, COUNT(1) AS quantidade, local FROM V_LOCALIZACAO_USUARIOS WHERE hashtag = "
  query <- paste(query, "'#", hashtags[1], "'", sep = '')
  
  for(hashtag in hashtags[-1]){
    query <- paste(query, " OR hashtag = '#", hashtag, "'", sep = '')
  }
  
  query <- paste(query, " GROUP BY latitude, longitude, local", sep = '')
  
  res <- dbSendQuery(con, query)
  
  data <- fetch(res, n = -1)
  
  dataFrame <- data.frame("longitude" = data$LONGITUDE, "latitude" = data$LATITUDE, "local" = data$LOCAL, "quantidade" = data$quantidade)
  
  dataFrame$q <- with(dataFrame, cut(quantidade, breaks = breaks))
  levels(dataFrame$q) <- paste(breaks_labels, "usu\u00e1rios")
  dataFrame$q <- as.ordered(dataFrame$q)
  
  geo <- list(
    scope = 'south america',
    showland = TRUE,
    landcolor = toRGB("gray95"),
    countrycolor = toRGB("gray80")
  )
  
  title <- paste('Mapa de usu\u00e1rios (', sum(dataFrame$quantidade), ') - ', nome_candidato,' - Brasil - Maio/Junho - 2018', sep = '')
  
  p <- plot_geo(dataFrame, sizes = c(5, 300), width = 1024, height = 600) %>%
    add_markers(
      x = ~longitude, y = ~latitude, size = ~quantidade, color = ~q,
      text = ~paste(dataFrame$local, " - ", dataFrame$quantidade, 'usu\u00e1rio(s)')
    )%>%
  layout(title = title, geo = geo)
  return (p)
}
